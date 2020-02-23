<?php
namespace App\Helper;

/**
 * Class Toastr
 * @package App\Helper
 *
 * @method static success(string $message, string $title = '')
 * @method static info(string $message, string $title = '')
 * @method static warning(string $message, string $title = '')
 * @method static error(string $message, string $title = '')
 */
class Toastr
{
    public static function __callStatic($name, $arguments)
    {
        return static::_handle($name, $arguments[0], $arguments[1]);
    }

    private static function _handle($name, $message, $title)
    {
        return "toastr.{$name}(\"".$message."\", \"".$title."\");";
    }
}