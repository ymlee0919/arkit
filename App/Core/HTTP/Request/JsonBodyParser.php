<?php

namespace Arkit\Core\HTTP\Request;

class JsonBodyParser extends RequestBodyParser
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