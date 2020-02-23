<?php
namespace App\Library\Mty95;

use App\Services;

class Widgets
{
    public static function show(string $command, string $options)
    {
        [$class, $method] = explode(':', $command);
        $params = [];

        $class = Services::getSharedInstance($class);

        return $class->{$method}($params);
    }
}