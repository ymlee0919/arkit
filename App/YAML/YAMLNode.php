<?php

/**
 * A node, used by YAMLParser for parsing YAML.
 * @package YAMLParser
 */
class YAMLNode
{
    public $parent;
    public $id;
    public $data;
    public $indent;
    public $children = false;

    static protected $lastNodeId = 0;

    /**
     * The constructor assigns the node a unique ID.
     * @return void
     */
    public function __construct()
    {
        $this->id = ++self::$lastNodeId;
    }
}