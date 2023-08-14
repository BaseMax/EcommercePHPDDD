<?php

namespace App\Application\Validator;

class RequiredValidator implements IsValidator
{
    public function validate($key, $data, $extera = null){
        if($data && array_key_exists($key, $data)){
            return true;
        }
        return $key.' is required';
    }
}