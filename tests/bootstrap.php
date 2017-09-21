<?php

require_once('classes/messages.php');
require_once('classes/instance.php');

class Session
{
    private static $flash = [];

    public static function get_flash($key, $default)
    {
        if (!isset(self::$flash[$key])) {
            return $default;
        }

        $flash = self::$flash[$key];
        unset(self::$flash[$key]);

        return $flash;
    }

    public static function set_flash($name, $data)
    {
        self::$flash[$name] = $data;
    }

    public static function reset()
    {
        self::$flash = [];
    }
}

class Event
{
    public static function register()
    {
    }
}

class Response
{
    public static $mock;

    public static function redirect($url, $method, $code)
    {
        call_user_func_array(self::$mock, [$url, $method, $code]);
    }
}

class Validation_Error extends \Exception
{
    public function __construct($message)
    {
        $this->text = $message;
    }

    public function get_message()
    {
        return $this->text;
    }
}
