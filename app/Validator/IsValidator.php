<?php

namespace App\Validator;

interface IsValidator
{
    public function validate($key, $data, $extera = null);
}