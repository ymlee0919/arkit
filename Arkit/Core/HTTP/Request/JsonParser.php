<?php

namespace Arkit\Core\HTTP\Request;

/**
 * Parser of request payload in json format
 */
class JsonParser extends PayloadParserInterface
{

    /**
     * @inheritDoc
     */
    public function parse(string $bodyContent): void
    {
        $this->values = json_decode($bodyContent, true);
        if(!is_array($this->values))
            $this->values = [];
    }
}