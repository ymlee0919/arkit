<?php

namespace Arkit\Core\Config;


/**
 * Environment-specific configuration handler.
 * Taken form https://github.com/vlucas/phpdotenv 
 * 
 * @package Arkit\Core\Config
 */
class DotEnv implements \ArrayAccess
{
    /**
     * The directory where the .env file can be located.
     *
     * @var string
     */
    private $path;

    /**
     * Constructor of the class
     *
     * @param string $path Absolute path to environment configuration file
     * @param string $file File name (default .env)
     */
    public function __construct(string $path, string $file = '.env')
    {
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
    }

    /**
     * The main entry point, will load the .env file and process it
     * so that we end up with all settings in the PHP environment vars
     * (i.e. getenv(), $_ENV, and $_SERVER)
     */
    public function init(): bool
    {
        if(!is_file($this->path))
            return false;

        $vars = $this->parse();

        return $vars !== null;
    }

    /**
     * Parse the .env file into an array of key => value
     */
    private function parse(): ?array
    {
        // We don't want to enforce the presence of a .env file, they should be optional.
        if (! is_file($this->path)) {
            return null;
        }

        // Ensure the file is readable
        if (! is_readable($this->path)) {
            throw new \InvalidArgumentException("The .env file is not readable: {$this->path}");
        }

        $vars = [];

        $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            // Is it a comment?
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // If there is an equal sign, then we know we are assigning a variable.
            if (strpos($line, '=') !== false) {
                [$name, $value] = $this->normaliseVariable($line);
                $vars[$name]    = $value;
                $this->setVariable($name, $value);
            }
        }

        return $vars;
    }

    /**
     * Sets the variable into the environment. Will parse the string
     * first to look for {name}={value} pattern, ensure that nested
     * variables are handled, and strip it of single and double quotes.
     */
    private function setVariable(string $name, string $value = '')
    {
        if (! getenv($name, true)) {
            putenv("{$name}={$value}");
        }

        if (empty($_ENV[$name])) {
            $_ENV[$name] = $value;
        }

        if (empty($_SERVER[$name])) {
            $_SERVER[$name] = $value;
        }
    }

    /**
     * Parses for assignment, cleans the $name and $value, and ensures
     * that nested variables are handled.
     */
    private function normaliseVariable(string $name, string $value = ''): array
    {
        // Split our compound string into its parts.
        if (strpos($name, '=') !== false) {
            [$name, $value] = explode('=', $name, 2);
        }

        $name  = trim($name);
        $value = trim($value);

        // Sanitize the name
        $name = preg_replace('/^export[ \t]++(\S+)/', '$1', $name);
        $name = str_replace(['\'', '"'], '', $name);

        // Sanitize the value
        $value = $this->sanitizeValue($value);
        $value = $this->resolveNestedVariables($value);

        return [$name, $value];
    }

    /**
     * Strips quotes from the environment variable value.
     *
     * @throws \InvalidArgumentException
     */
    private function sanitizeValue(string $value): string
    {
        if (! $value) {
            return $value;
        }

        // Does it begin with a quote?
        if (strpbrk($value[0], '"\'') !== false) {
            // value starts with a quote
            $quote = $value[0];

            $regexPattern = sprintf(
                '/^
                %1$s          # match a quote at the start of the value
                (             # capturing sub-pattern used
                 (?:          # we do not need to capture this
                 [^%1$s\\\\] # any character other than a quote or backslash
                 |\\\\\\\\   # or two backslashes together
                 |\\\\%1$s   # or an escaped quote e.g \"
                 )*           # as many characters that match the previous rules
                )             # end of the capturing sub-pattern
                %1$s          # and the closing quote
                .*$           # and discard any string after the closing quote
                /mx',
                $quote
            );

            $value = preg_replace($regexPattern, '$1', $value);
            $value = str_replace("\\{$quote}", $quote, $value);
            $value = str_replace('\\\\', '\\', $value);
        } else {
            $parts = explode(' #', $value, 2);
            $value = trim($parts[0]);

            // Unquoted values cannot contain whitespace
            if (preg_match('/\s+/', $value) > 0) {
                throw new \InvalidArgumentException('.env values containing spaces must be surrounded by quotes.');
            }
        }

        return $value;
    }

    /**
     *  Resolve the nested variables.
     *
     * Look for ${varname} patterns in the variable value and replace with an existing
     * environment variable.
     * 
     */
    private function resolveNestedVariables(string $value): string
    {
        if (strpos($value, '$') !== false) {
            $value = preg_replace_callback(
                '/\${([a-zA-Z0-9_\.]+)}/',
                function ($matchedPatterns) {
                    $nestedVariable = $this->getVariable($matchedPatterns[1]);

                    if ($nestedVariable === null) {
                        return $matchedPatterns[0];
                    }

                    return $nestedVariable;
                },
                $value
            );
        }

        return $value;
    }

    /**
     * Search the different places for environment variables and return first value found.
     *
     * @return string|null
     */
    private function getVariable(string $name)
    {
        switch (true) {
            case array_key_exists($name, $_ENV):
                return $_ENV[$name];

            case array_key_exists($name, $_SERVER):
                return $_SERVER[$name];

            default:
                $value = getenv($name);

                // switch getenv default to null
                return $value === false ? null : $value;
        }
    }

    /**
     * Override ArrayAccess::offsetExists method.
     * This method is executed when using isset() or empty() on objects implementing ArrayAccess. 
     *
     * @param mixed $offset An offset to check for
     * @return boolean
     * 
     */
    public function offsetExists(mixed $offset): bool
    {
        if(array_key_exists($offset, $_ENV))
            return true;

        if(array_key_exists($offset, $_SERVER))
            return true;

        return getenv($offset) !== false;
    }

    /**
     * Override ArrayAccess::offsetGet method.
     *
     * @param mixed $offset Offset to retrieve
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->getVariable($offset);
    }

    /**
     * Override ArrayAccess::offsetSet method.
     * Assign a value to the specified offset.
     *
     * @param mixed $offset The offset to assign the value to
     * @param mixed $value The value to set
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->setVariable($offset, $value);
    }

    /**
     * Override ArrayAccess::offsetUnset method.
     * Unsets an offset
     *
     * @param mixed $offset The offset to unset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        if(isset($_ENV[$offset]))
            unset($_ENV[$offset]);

        if(isset($_SERVER[$offset]))
            unset($_SERVER[$offset]);
    }
}
