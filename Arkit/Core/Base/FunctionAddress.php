<?php

namespace Arkit\Core\Base;

/**
 * Class to store a class name and a method name.
 * 
 */
class FunctionAddress
{

    /**
     * @var string Name of the class
     */
    private string $className;

    /**
     * @var string|null Name of the funcion
     */
    private ?string $functionName;

    /**
     * Build an instance of FunctionAddress given an string.
     * String format: ClassName[::FunctionName]
     * 
     * Note that FunctionName is optional.
     * 
     * @param string $strFunctionAddress String with format: ClassName[::FunctionName]
     * @return FunctionAddress
     * @example FunctionAddress::fromString('\Administration\Dashboard\Controller::ShowDashboard') Return and object with class name \Administration\Dashboard\Controller and method name ShowDashboard
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
     * Constructor of the class
     * 
     * @param string $className Name of the class
     * @param string|null $functionName Function name
     */
    public function __construct(string $className, ?string $functionName = null)
    {
        $this->className = $className;
        $this->functionName = $functionName;
    }

    /**
     * Return the class name
     * @return string
     */
    public function getClassName(): string
    {
        return $this->className;
    }

    /**
     * Return the function name
     * @return string|null
     */
    public function getFunctionName(): ?string
    {
        return $this->functionName;
    }

}