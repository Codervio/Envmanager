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
            self::$sysvar = $this->parse();
        }
    }

    public function parse()
    {
        if (!empty($_ENV)) {
            return $_ENV;
        }

        if (function_exists('getenv')) {
            return getenv();
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
