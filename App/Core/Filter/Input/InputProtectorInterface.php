<?php

namespace Arkit\Core\Filter\Input;

interface InputProtectorInterface
{
    public function init(array &$config) : void;

    public function generateProtectionCode(string $formId, ?int $expire = null) : string;

    public function generateCookie(string $formId, ?int $expire = null, string $path = '/') : void;

    public function validateProtectionCode(string $formId, string $code) : string;

    public function validateCookie(string $formId) : bool;
}