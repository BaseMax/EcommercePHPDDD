<?php

namespace App\Application\Validator;

class RequiredValidator implements IsValidator
{
    public function validate(string $key, array $data, string $extera = null) : bool|string {
        if($data && array_key_exists($key, $data)){
            return true;
        }
        return $key.' is required';
    }
}