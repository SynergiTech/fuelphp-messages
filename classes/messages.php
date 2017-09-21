<?php

namespace SynergiTech;

class Messages
{
    protected static $instances = array();

    public static function instance($instance = null)
    {
        if ($instance === null) {
            $instance = 'default';
        }

        if (!isset(static::$instances[$instance])) {
            static::$instances[$instance] = new Messages\Instance($instance);
        }

        return static::$instances[$instance];
    }

    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function error($title, $text = null)
    {
        return static::instance()->error($title, $text);
    }

    public static function info($title, $text = null)
    {
        return static::instance()->info($title, $text);
    }

    public static function warning($title, $text = null)
    {
        return static::instance()->warning($title, $text);
    }

    public static function success($title, $text = null)
    {
        return static::instance()->success($title, $text);
    }

    public static function reset()
    {
        return static::instance()->reset();
    }

    public static function resetAll()
    {
        foreach (self::$instances as $instance) {
            $instance->reset();
        }
        self::$instances = [];
    }

    public static function keep()
    {
        return static::instance()->keep();
    }

    public static function any()
    {
        return static::instance()->any();
    }

    public static function get($type = null)
    {
        return static::instance()->get($type);
    }

    public static function redirect($url = '', $method = 'location', $code = 302)
    {
        return static::instance()->redirect($url, $method, $code);
    }
}
