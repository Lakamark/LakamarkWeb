<?php

namespace App\Helper;

class PathGenerator
{
    public static function join(string ...$parts): string
    {
        return preg_replace('~[/\\\\]+~', DIRECTORY_SEPARATOR, implode(DIRECTORY_SEPARATOR, $parts)) ?: '';
    }
}
