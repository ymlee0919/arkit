<?php

namespace Arkit\Core\HTTP\Request;

class UrlEncodedParser extends BodyParserInterface
{

    /**
     * @inheritDoc
     */
    public function parse(string $bodyContent): void
    {
        // Parse the content
        parse_str($bodyContent, $this->values);
    }
}