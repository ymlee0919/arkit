<?php

/**
 *
 */
class FunctionAddress
{

    /**
     * @var string
     */
    private string $filePart;

    /**
     * @var string
     */
    private string $className;

    /**
     * @var string|null
     */
    private ?string $functionName;

    /**
     * @param string $strFunctionAddress
     * @return ?FunctionAddress
     */
    public static function fromString(string $strFunctionAddress) : ?FunctionAddress
    {
        $items = [];
        if(str_contains($strFunctionAddress, '::'))
        {
            $result = preg_match_all("/^([A-Za-z._-]+)\/([A-Za-z._-]+)::([A-Za-z._-]+)$/", $strFunctionAddress, $items);
            if(!$result || !isset($items[3]))
                return null;

            return new FunctionAddress($items[1][0], $items[2][0], $items[3][0]);
        }
        else
        {
            $result = preg_match_all("/^([A-Za-z._-]+)\/([A-Za-z._-]+)$/", $strFunctionAddress, $items);
            if(!$result || !isset($items[2]))
                return null;

            return new FunctionAddress($items[1][0], $items[2][0]);
        }

    }

    /**
     * @param string $fileAddress
     * @param string $className
     * @param string|null $functionName
     */
    public function __construct(string $fileAddress, string $className, ?string $functionName = null)
    {
        $this->filePart = $fileAddress;
        $this->className = $className;
        $this->functionName = $functionName;
    }

    /**
     * @return string
     */
    public function getFilePart(): string
    {
        return $this->filePart;
    }

    /**
     * @param string $startingPath
     * @return string
     */
    public function getFileFrom(string $startingPath): string
    {
        return $startingPath. (str_ends_with($startingPath, '/') ? '' : '/') . str_replace('.', '/', $this->filePart);
    }

    /**
     * @param string ...$directories
     * @return bool
     */
    public function importFrom(string ...$directories): bool
    {
        return import($this->className, implode('.', $directories) . '.' . $this->filePart);
    }

    /**
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * @return string|null
     */
    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

}