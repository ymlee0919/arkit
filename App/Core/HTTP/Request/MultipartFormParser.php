<?php

namespace Arkit\Core\HTTP\Request;

class MultipartFormParser extends RequestBodyParser
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