<?php

require 'RoutingRule.php';
require 'RoutingCallback.php';

interface RouterInterface
{
    /**
     * Set a routing rule.
     *
     * Rule must contain:
     *
     *      url: '/url'
     *      callback: 'model.controller.file/Class::Function'
     *      method: 'POST'
     *      constraints (optional):
     *          [parameterName]: Regular expression for url get parameters
     *      allow (optional): [List of get parameters allowed into the URL]
     *
     * @param string $ruleId Rule Id
     * @param array $rule Array with routing information
     * @returns void
     */
    public function setRule(string $ruleId, array &$rule) : void;

    /**
     * Set array of routing rules.
     *
     * Rules format:
     *
     * rulerId:
     *      url: '/url'
     *      callback: 'model.controller.file/Class::Function'
     *      method: 'POST'
     *      constraints (optional):
     *          <parameter>: Regular expression for url get parameters
     *      allow (optional): [List of get parameters allowed into the URL]
     *
     * @param array $rules
     * @returns void
     */
    public function setRules(array &$rules) : void;


    /**
     * Get a routing rule given the ruleId
     *
     * @param string $ruleId
     * @return RoutingRule|null
     */
    public function getRule(string $ruleId) : ?RoutingRule;

    /**
     * Return and array with the given match rule.
     *
     * The array returned mush have:
     *  - id: The rule id
     *  - callback: The callback function [Directory.directory..file/Class::function]
     *  - parameters: Parameters set by url
     *
     * @param string $url Requested url
     * @param string $method Requested method
     * @return ?RoutingCallback
     */
    public function route(string $url, string $method) : ?RoutingCallback;

    /**
     * Set sign for caching
     *
     * @param string $str
     * @returns void
     */
    public function setSign(string $str) : void;

    /**
     * Get sign for caching
     *
     * @return string
     */
    public function getSign() : string;

    /**
     * Build the url given the ruleId and the array of parameters
     *
     * @param string $ruleId ID of the rule
     * @param ?array $params (Optional) Parameters of the URL
     * @return string
     */
    public function buildUrl(string $ruleId, ?array $params = null) : string;
}