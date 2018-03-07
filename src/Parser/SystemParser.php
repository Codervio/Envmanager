<?php

namespace Codervio\Envmanager\Parser;

class SystemParser
{
    public static $isCalled = false;
    public static $sysvar = array();

    public function __construct()
    {
        if (!self::$isCalled) {
            self::$isCalled = true;
            self::$sysvar = static::parse();
        }
    }

    public static function parse()
    {
        if (function_exists('getenv')) {
            return getenv();
        }

        if (!empty($_ENV)) {
            return $_ENV;
        }
    }

    public static function getValues($variable = null)
    {
        if (!is_null($variable)) {
            if (isset(self::$sysvar[$variable])) {
                return self::$sysvar[$variable];
            } else {
                return null;
            }
        }

        return static::getAllEnvVariables();
    }

    public static function checkValue($variable)
    {
        if (isset(self::$sysvar[$variable])) {
            return true;
        }

        return false;
    }

    private static function getAllEnvVariables()
    {
        return static::$sysvar;
    }
}
