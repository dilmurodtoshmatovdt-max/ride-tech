<?php

namespace App\Facades\Helper;

class Helper
{
    public function stringifyArrayKeys($sourceArray)
    {
        $context = [];
        array_map(function ($key, $value) use ($context) {
            $context[strval($key)] = $value;
            return;
        }, array_keys($sourceArray), array_values($sourceArray));

        return $context;
    }
}
