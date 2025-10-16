<?php

namespace App\Services\Shared\Helper;

use Carbon\Carbon;
use Illuminate\Support\Stringable;

class ResponseHelperService
{
    public static function arrayRecursiveChangeDateFormat(&$item, $key)
    {
        if (str_ends_with($key, '_at') || $key == 'birthday') {
            try {
                if ($item && !empty($item)) {
                    $item = (Carbon::parse($item)->timestamp * 1000);
                }
            } catch (\Throwable $th) {
                logger()->info("Couldn't parse " . $key . " to timestamp");
            }
        } elseif (str_ends_with($key, '_id') && !empty($item)) {
            $item = (int)$item;
        } elseif (str_ends_with($key, '_count')) {
            $item = (int)$item;
        } elseif (str_starts_with($key, 'is_')) {
            $item = (bool)$item;
        } elseif (is_array(value: $item)) {
            array_walk_recursive($item, 'self::arrayRecursiveChangeDateFormat');
        } elseif (gettype($item) == 'object') {
            if (!$item instanceof Stringable) {
                try {
                    $item2 = json_decode($item, true);
                    if (is_array($item2)) {
                        array_walk_recursive($item2, 'self::arrayRecursiveChangeDateFormat');
                    }
                    $item = $item2;
                } catch (\Throwable $th) {
                    logger()->error($th);
                }
            }
        }
    }
}
