<?php

namespace Codervio\Envmanager\Resolver;

use Codervio\Envmanager\Resolver\AbstractResolver;
use Codervio\Envmanager\Resolver\ValueResolverInterface;
use Codervio\Envmanager\Exceptions\ValueException;

class ValueResolver extends AbstractResolver implements ValueResolverInterface
{
    public function execute($value, $strict)
    {
        /**
         * Trim comments
         */
        $value = $this->trimComments($value);

        /**
         * Throw if failed one of ' or " sign
         */
        $value = $this->checkSurroundingStripes($value);

        /**
         * Trim on both side quotes and marks
         */
        $value = $this->trimSlashes($value);

        /**
         * Trim spaces
         */
        $value = trim($value);

        /**
         * Check 'abc' or "abc"
         */
        //$result = $this->checkInvalidSign($result);

        /**
         * Trim empty '' or ""
         */
        $value = $this->trimEmpty($value);

        /**
         * Remove "" and ''
         * Fix it on TK3 crashed
         */
        $value = $this->trimClosers($value);

        /**
         * Remove new lines
         */
        $value = $this->trimNewLines($value);

        /**
         * Trim backslashes "\" sign
         */
        $value = $this->trimBackslashes($value);

        /**
         * Parsing values
         */
        $value = $this->valueparser->parse($value, $strict);

        return $value;
    }

    public function resolveComment($value)
    {
        preg_match_all(self::GET_COMMENT, $value, $matches, PREG_SET_ORDER, 0);

        if (isset($matches[0][2])) {
            return trim($matches[0][2]);
        }

        return false;
    }

    private function trimBackslashes($result)
    {
        $result =  preg_replace('/\\\\/', '\\', $result);

        return stripslashes($result);
    }

    private function trimSlashes($input)
    {
        if (substr($input, 0, 1) === '"') {
            $input = ltrim($input, '"');
        }

        if (substr($input, -1) === '"') {
            $input = rtrim($input, '"');
        }

        if (substr($input, 0, 1) === '\'') {
            $input = ltrim($input, '\'');
        }

        if (substr($input, -1) === '\'') {
            $input = rtrim($input, '\'');
        }

        return $input;
    }

    private function trimEmpty($result)
    {
        if ($result === '""' or $result === '\'\'') {
            return '';
        }

        return $result;
    }

    private function trimNewLines($result)
    {
        return str_replace(array(self::UNIX_NL, self::DOS_NL, self::MAC_NL), '', $result);
    }

    private function checkInvalidSign($value)
    {
        if (preg_match(self::INVALID_PREG, $value)) {

            throw new ValueException(sprintf("Invalid format type: %s", $value));
        }

        return $value;
    }

    private function trimClosers($input)
    {
        $input = preg_replace('/^\'|\'$/', '', $input);
        $input = preg_replace('/^\"|\"$/', '', $input);

        return $input;
    }

    private function trimComments($input)
    {
        preg_match_all(self::EXPLODE_COMMENTS, $input, $matches, PREG_SET_ORDER, 0);

        if (isset($matches[0][3])) {
            return $matches[0][3];
        }

        if (isset($matches[0][1])) {
            return $matches[0][1];
        }

        return $input;
    }

    private function checkSurroundingStripes($input)
    {
        if (preg_match('/(.*)\s+(.*)/i', $input)) {
            $rep = '/(^["]+(.*)+["])|(^[\']+(.*)+[\'])/i';
            if (!preg_match_all($rep, $input, $v)) {
                throw new ValueException(sprintf("A value with spaces should be closed with \" closure. Error on string: '%s' sign", $input));
            }
        }

        return $input;
    }
}
