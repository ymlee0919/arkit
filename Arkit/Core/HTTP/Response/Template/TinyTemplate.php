<?php

namespace Arkit\Core\HTTP\Response\Template;

/**
 * Tiny template engine. It only allow direct variables replacement.
 * 
 * It can be used not only for html format.
 */
class TinyTemplate
{
    /**
     * Location of the template
     * 
     * @var string
     */
    private string $templateFolder;

    /**
     * Values assigned to the template
     * @var array
     */
    private array $values;

    /**
     * Left delimiter
     * 
     * @var string
     */
    private string $leftDelimiter;

    /**
     * Rigth delimiter
     * 
     * @var string
     */
    private string $rightDelimiter;

    /**
     * Constructor of the class
     * 
     * @param string $templateFolder Absolute path of the location of the template.
     */
    public function __construct(string $templateFolder)
    {
        $this->templateFolder = $templateFolder;
        $this->values = [];
        $this->leftDelimiter = '{{';
        $this->rightDelimiter = '}}';
    }

    /**
     * Change delimiters used into the template
     * 
     * @param string $left Left delimiter
     * @param string $right Right delimiter
     * @return void
     */
    public function setDelimiters(string $left, string $right): void
    {
        $this->leftDelimiter = $left;
        $this->rightDelimiter = $right;
    }

    /**
     * Assign a value to the template
     * 
     * @param string $fieldName Name of the variable of the template
     * @param mixed $value Assigned value
     * @param bool $encodeFirst Encode to html entities before compile the template
     * @param bool $toUtf8 Encode to utf-8. Only works if $encodeFirst is true
     * @return void
     */
    public function assign(string $fieldName, mixed $value, bool $encodeFirst = true, bool $toUtf8 = false): void
    {
        if ($toUtf8) $value = utf8_encode($value);
        if ($encodeFirst) $value = htmlentities($value);

        $this->values[$fieldName] = $value;
    }

    /**
     * Compile a small template
     * 
     * @param string $sourceTpl Name of the template file
     * 
     * @return string Compiled template
     */
    public function fetch(string $sourceTpl): string
    {
        $hash = [];
        foreach ($this->values as $key => $value)
            $hash[$this->leftDelimiter . '$' . $key . $this->rightDelimiter] = $value;

        $fullFilePath = $this->templateFolder . '/' . $sourceTpl;
        return strtr(file_get_contents($fullFilePath), $hash);
    }

} 