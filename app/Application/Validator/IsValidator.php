<?php

namespace App\Application\Validator;

interface IsValidator
{
    public function validate(string $key, array $data, string $extera = null) : bool|string;
}