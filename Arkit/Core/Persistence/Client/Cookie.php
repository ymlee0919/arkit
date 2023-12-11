<?php

namespace Arkit\Core\Persistence\Client;

class Cookie implements CookieInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var int
     */
    protected $expires;

    /**
     * @var string
     */
    protected $path = '/';

    /**
     * @var string
     */
    protected $domain = '';

    /**
     * @var bool
     */
    protected $secure = false;

    /**
     * @var bool
     */
    protected $httponly = true;

    /**
     * @var string
     */
    protected $samesite = 'Lax';

    /**
     * @var bool
     */
    protected $raw = false;

    /**
     * Default attributes for a Cookie object. The keys here are the
     * lowercase attribute names. Do not camelCase!
     *
     * @var array<string, mixed>
     */
    private static array $defaults = [
        'expires' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => false,
        'httponly' => true,
        'samesite' => self::SAMESITE_LAX
    ];

    /**
     * A cookie name can be any US-ASCII characters, except control characters,
     * spaces, tabs, or separator characters.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie#attributes
     * @see https://tools.ietf.org/html/rfc2616#section-2.2
     */
    private static string $reservedCharsList = "=,; \t\r\n\v\f()<>@:\\\"/[]?{}";

    /**
     * Set the default attributes to a Cookie instance
     *
     * @param array<string, mixed> $config
     * @return array<string, mixed> The old defaults array. Useful for resetting.
     */
    public static function setDefaults(array $config = []): array
    {
        $oldDefaults = self::$defaults;
        $newDefaults = $config;
        self::$defaults = $newDefaults + $oldDefaults;

        return $oldDefaults;
    }

    /**
     * Create Cookie instance from "set-cookie" header string.
     *
     * @param string $cookie Cookie header string.
     * @param array<string, mixed> $defaults Default attributes.
     * @return self|null
     */
    public static function fromHeaderString(string $cookie, array $defaults = []): self|null
    {
        if (str_contains($cookie, '";"')) {
            $cookie = str_replace('";"', '{__cookie_replace__}', $cookie);
            $parts = str_replace('{__cookie_replace__}', '";"', explode(';', $cookie));
        } else {
            $parts = preg_split('/\;[ \t]*/', $cookie);
        }

        [$name, $value] = explode('=', array_shift($parts), 2);
        $data = [
                'name' => urldecode($name),
                'value' => urldecode($value),
            ] + $defaults;

        if (!self::isValidName($data['name']))
            return null;

        foreach ($parts as $part) {
            if (str_contains($part, '=')) {
                [$key, $value] = explode('=', $part);
            } else {
                $key = $part;
                $value = true;
            }

            $key = strtolower($key);
            $data[$key] = $value;
        }

        if (isset($data['max-age'])) {
            $data['expires'] = time() + (int)$data['max-age'];
            unset($data['max-age']);
        }

        if (isset($data['samesite'])) {
            // Ignore invalid value when parsing headers
            // https://tools.ietf.org/html/draft-west-first-party-cookies-07#section-4.1
            if (!in_array($data['samesite'], CookieInterface::ALLOWED_SAMESITE_VALUES, true)) {
                unset($data['samesite']);
            }
        }

        $name = (string)$data['name'];
        $value = (string)$data['value'];
        unset($data['name'], $data['value']);

        return new Cookie($name, $value, $data);
    }

    /**
     * Factory method to create Cookie instances.
     *
     * @param string $name Cookie name
     * @param array|string $value Value of the cookie
     * @param array<string, mixed> $options Cookies options.
     * @return ?Cookie
     */
    public static function create(string $name, array|string $value, array $options = []): ?Cookie
    {
        // Validate the name
        if (!self::isValidName($name))
            return null;

        $options += static::$defaults;
        $options['expires'] = self::convertExpiresTimestamp($options['expires']);

        // Validate the expires value
        if (is_null($options['expires']))
            return null;

        // Validate the samesite value
        if (isset($options['samesite']))
            if (!self::isValidSameSite($options['samesite'], ($options['secure'] ?? self::$defaults['secure'])))
                return null;

        return new static($name, $value, $options);
    }

    /**
     * Constructor
     *
     * The constructors args are similar to the native PHP `setcookie()` method.
     * The only difference is the 3rd argument which excepts null or an
     * DateTime or DateTimeImmutable object instead an integer.
     *
     * @link https://php.net/manual/en/function.setcookie.php
     * @param string $name Cookie name
     * @param string $value Value of the cookie
     * @param ?int $expiresAt Expiration time and date
     * @param string|null $path Path
     * @param string|null $domain Domain
     * @param bool|null $secure Is secure
     * @param bool|null $httpOnly HTTP Only
     * @param string|null $sameSite Samesite
     */
    public static function build(
        string  $name,
        string  $value = '',
        ?int    $expiresAt = null,
        ?string $path = null,
        ?string $domain = null,
        ?bool   $secure = null,
        ?bool   $httpOnly = null,
        ?string $sameSite = null
    ): ?Cookie
    {
        if (!self::isValidName($name))
            return null;

        $cookie = new Cookie($name, $value);

        $cookie->expires = (!!$expiresAt) ? self::convertExpiresTimestamp($expiresAt) : static::$defaults['expires'];
        $cookie->domain = $domain ?? static::$defaults['domain'];
        $cookie->httponly = $httpOnly ?? static::$defaults['httponly'];
        $cookie->path = $path ?? static::$defaults['path'];
        $cookie->secure = $secure ?? static::$defaults['secure'];
        $cookie->samesite = $sameSite ?? static::$defaults['samesite'];

        if (!self::isValidSameSite($cookie->samesite, $cookie->secure))
            return null;

        return $cookie;
    }

    /**
     * Validates the cookie name per RFC 2616.
     */
    public static function isValidName(string $name): bool
    {
        if (empty($name))
            return false;

        return (strpbrk($name, self::$reservedCharsList) === false);
    }

    /**
     * Validates the `SameSite` to be within the allowed types.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie/SameSite
     */
    protected static function isValidSameSite(string $samesite, bool $secure): bool
    {
        if ($samesite === '')
            $samesite = self::$defaults['samesite'];

        if ($samesite === '')
            $samesite = self::SAMESITE_LAX;

        if (!in_array(strtolower($samesite), self::ALLOWED_SAMESITE_VALUES, true))
            return false;

        if (strtolower($samesite) === self::SAMESITE_NONE && !$secure)
            return false;

        return true;
    }

    /**
     * Converts expires time to Unix format.
     *
     * @param \DateTimeInterface|int|string $expires
     * @return int|null
     */
    protected static function convertExpiresTimestamp(\DateTimeInterface|int|string $expires = 0): int|null
    {
        if ($expires instanceof \DateTimeInterface)
            $expires = $expires->format('U');

        if (!is_string($expires) && !is_int($expires))
            return null;

        if (!is_numeric($expires)) {
            $expires = strtotime($expires);

            if ($expires === false)
                return null;
        }

        return $expires > 0 ? (int)$expires : 0;
    }

    /**
     * Construct a new Cookie instance.
     *
     * @param string $name The cookie's name
     * @param string $value The cookie's value
     * @param array<string, mixed> $options The cookie's options
     */
    private function __construct(string $name, string $value = '', array $options = [])
    {
        $options += self::$defaults;

        // If both `Expires` and `Max-Age` are set, `Max-Age` has precedence.
        if (isset($options['max-age']) && is_numeric($options['max-age'])) {
            $options['expires'] = time() + (int)$options['max-age'];
            unset($options['max-age']);
        }

        // empty string SameSite should use the default for browsers
        $samesite = $options['samesite'] ?: self::$defaults['samesite'];

        $this->name = $name;
        $this->value = $value;
        $this->expires = ($options['expires'] > 0) ? (int)$options['expires'] : 0;
        $this->path = $options['path'] ?: self::$defaults['path'];
        $this->domain = $options['domain'] ?: self::$defaults['domain'];
        $this->secure = $options['secure'];
        $this->httponly = $options['httponly'];
        $this->samesite = ucfirst(strtolower($samesite));
    }

    /**
     * @inheritDoc
     */
    public function getId(): string
    {
        return implode(';', [$this->name, $this->path, $this->domain]);
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function setName(string $name): self
    {
        if (self::isValidName($name))
            $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getExpiresTimestamp(): int
    {
        return $this->expires;
    }

    /**
     * @inheritDoc
     */
    public function getExpiresString(): string
    {
        return gmdate(self::EXPIRES_FORMAT, $this->expires);
    }

    /**
     * @inheritDoc
     */
    public function setExpired(): self
    {
        $this->expires = time() - 360;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isExpired(): bool
    {
        return $this->expires === 0 || $this->expires < time();
    }

    /**
     * @inheritDoc
     */
    public function getMaxAge(): int
    {
        $maxAge = $this->expires - time();

        return max($maxAge, 0);
    }

    /**
     * @inheritDoc
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function setPath(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getDomain(): string
    {
        return $this->domain;
    }

    /**
     * @inheritDoc
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @inheritDoc
     */
    public function isHTTPOnly(): bool
    {
        return $this->httponly;
    }

    /**
     * @inheritDoc
     */
    public function getSameSite(): string
    {
        return $this->samesite;
    }

    /**
     * @inheritDoc
     */
    public function getOptions(): array
    {
        // This is the order of options in `setcookie`
        return [
            'expires' => $this->expires,
            'path' => $this->path,
            'domain' => $this->domain,
            'secure' => $this->secure,
            'httponly' => $this->httponly,
            'samesite' => $this->samesite ?: ucfirst(self::SAMESITE_LAX),
        ];
    }

    /**
     * @inheritDoc
     */
    public function toHeaderString(): string
    {
        return $this->__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $cookieHeader = [];

        if ($this->getValue() === '') {
            $cookieHeader[] = $this->name . '=deleted';
            $cookieHeader[] = 'Expires=' . gmdate(self::EXPIRES_FORMAT, 0);
            $cookieHeader[] = 'Max-Age=0';
        } else {
            $value = rawurlencode($this->getValue());

            $cookieHeader[] = sprintf('%s=%s', $this->name, $value);

            if ($this->getExpiresTimestamp() !== 0) {
                $cookieHeader[] = 'Expires=' . $this->getExpiresString();
                $cookieHeader[] = 'Max-Age=' . $this->getMaxAge();
            }
        }

        if ($this->path !== '') {
            $cookieHeader[] = 'Path=' . $this->path;
        }

        if ($this->domain !== '') {
            $cookieHeader[] = 'Domain=' . $this->domain;
        }

        if ($this->secure) {
            $cookieHeader[] = 'Secure';
        }

        if ($this->httponly) {
            $cookieHeader[] = 'HttpOnly';
        }

        $samesite = $this->samesite;

        if ($samesite === '') {
            // modern browsers warn in console logs that an empty SameSite attribute
            // will be given the `Lax` value
            $samesite = self::SAMESITE_LAX;
        }

        $cookieHeader[] = 'SameSite=' . ucfirst(strtolower($samesite));

        return implode('; ', $cookieHeader);
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
                'name' => $this->name,
                'value' => $this->value
            ] + $this->getOptions();
    }

    /**
     * @inheritDoc
     */
    public function dispatch(): void
    {
        setcookie($this->name, $this->value, $this->getOptions());
    }
}