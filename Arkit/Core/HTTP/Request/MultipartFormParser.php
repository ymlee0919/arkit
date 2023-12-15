<?php

namespace Arkit\Core\HTTP\Request;

/**
 * Parser of request payload in MultipartForm format
 */
class MultipartFormParser extends PayloadParserInterface
{

    /**
     * @inheritDoc
     */
    public function parse(string $bodyContent): void
    {
        $this->values = $_POST + [];
        unset($_POST);
    }
}