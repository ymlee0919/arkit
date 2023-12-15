<?php

namespace Arkit\Core\HTTP\Request;

/**
 * Parser of request payload in url enconded format. It is the default format.
 */
class UrlEncodedParser extends PayloadParserInterface
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