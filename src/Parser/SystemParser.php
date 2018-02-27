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
        if (function_exists('getenv')) {
            return getenv();
        }

        if (!is_array($_ENV)) {
            return $_ENV;
        }
    }

    public function getValues($variable = null)
    {
        if (!is_null($variable)) {
            if (isset(self::$sysvar[$variable])) {
                return self::$sysvar[$variable];
            } else {
                return null;
            }
        }

        return self::$sysvar;
    }

    public function checkValue($variable)
    {
        if (isset(self::$sysvar[$variable])) {
            return true;
        }

        return false;
    }
}