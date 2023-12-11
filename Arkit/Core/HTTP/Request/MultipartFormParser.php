<?php

namespace Arkit\Core\HTTP\Request;

class MultipartFormParser extends BodyParserInterface
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