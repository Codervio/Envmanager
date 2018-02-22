<?php

namespace Codervio\Envmanager\Parser;

class ValueParser
{
    public function parse($input, $strict)
    {
        $input = $this->parseEmpty($input, $strict);
        $input = $this->parseBool($input, $strict);
        $input = $this->parseFloat($input);
        $input = $this->parseNumeric($input);

        //$input = $this->parseVariable($input);

        return $input;
    }

    public function parseNumeric($input)
    {
        $var = filter_var($input, FILTER_VALIDATE_INT);

        if (ctype_digit($input) || is_int($var)) {
            return (int)$input;
        }

        return $input;
    }

    public function parseFloat($input)
    {
        $var = filter_var($input, FILTER_VALIDATE_FLOAT);

        if (is_float($var)) {
            return (float)$var;
        }

        return $input;
    }

    public function parseBool($var, $strict)
    {
        if ($strict) {

            if (preg_match('/true|yes|on|1|y/i', $var)) {
                return true;
            } else if (preg_match('/false|no|off|0|n/i', $var)) {
                return false;
            }

        } else {

            if (preg_match('/true|yes|on/i', $var)) {
                return true;
            } else if (preg_match('/false|no|off/i', $var)) {
                return false;
            }

        }

        return $var;
    }

    public function parseEmpty($var)
    {
        if (strlen($var) === 0) {
            return null;
        }

        return $var;
    }

    public function isBool($input)
    {
        $input = strtolower($input);

        return filter_var($input, FILTER_VALIDATE_BOOLEAN);
    }
}