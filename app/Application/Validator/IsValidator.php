<?php

namespace App\Application\Validator;

interface IsValidator
{
    public function validate($key, $data, $extera = null);
}