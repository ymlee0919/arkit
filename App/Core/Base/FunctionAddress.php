<?php

namespace Arkit\Core\Base;
/**
 *
 */
class FunctionAddress
{

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
     * @return FunctionAddress
     */
    public static function fromString(string $strFunctionAddress): FunctionAddress
    {
        if (str_contains($strFunctionAddress, '::')) {
            $parts = explode('::', $strFunctionAddress);
            return new FunctionAddress($parts[0], $parts[1]);
        } else {
            return new FunctionAddress($strFunctionAddress);
        }

    }

    /**
     * @param string $className
     * @param string|null $functionName
     */
    public function __construct(string $className, ?string $functionName = null)
    {
        $this->className = $className;
        $this->functionName = $functionName;
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