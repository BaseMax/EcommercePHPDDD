<?php

namespace App\Validator;

class UniqueValidator implements IsValidator
{
    public function validate($key, $data, $extera = null){
        $count = (new $extera)->where($key, $data[$key])->count();
        if($count != 0){
            return $key.' already exists on '.$extera;
        }
        return true;
    }
}