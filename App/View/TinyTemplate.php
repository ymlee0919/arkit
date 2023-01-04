<?php

/**
 * Class TinyTemplate
 */
class TinyTemplate
{
    /**
     * @var string
     */
    private string $templateFolder;

    /**
     * @var array
     */
    private array $values;

    /**
     * @var string
     */
    private string $leftDelimiter;

    /**
     * @var string
     */
    private string $rightDelimiter;

    /**
     * @param string $templateFolder
     */
    public function __construct(string $templateFolder)
    {
        $this->templateFolder = $templateFolder;
        $this->values = [];
        $this->leftDelimiter = '{{';
        $this->rightDelimiter = '}}';
    }

    /**
     * @param string $left
     * @param string $right
     * @return void
     */
    public function setDelimiters(string $left, string $right) : void
    {
        $this->leftDelimiter = $left;
        $this->rightDelimiter = $right;
    }

    /**
     * @param string $fieldName
     * @param mixed $value
     * @param bool $encodeFirst
     * @param bool $toUtf8
     * @return void
     */
    public function assign(string $fieldName, mixed $value, bool $encodeFirst = true, bool $toUtf8 = false) : void
    {
        if($toUtf8) $value = utf8_encode($value);
        if($encodeFirst) $value = htmlentities($value);

        $this->values[$fieldName] = $value;
    }

    /**
     * Compile a small template, generally used by emails
     * @param string $sourceTpl
     * @return string
     */
    public function fetch(string $sourceTpl) : string
    {
        $hash = [];
        foreach($this->values as $key => $value)
            $hash[$this->leftDelimiter . '$' . $key . $this->rightDelimiter] = $value;

        $fullFilePath = $this->templateFolder . '/' . $sourceTpl;
        return strtr(file_get_contents($fullFilePath), $hash);
    }

} 