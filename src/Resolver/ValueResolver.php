<?php

namespace Codervio\Envmanager\Resolver;

use Codervio\Envmanager\Resolver\AbstractResolver;
use Codervio\Envmanager\Resolver\ValueResolverInterface;
use Codervio\Envmanager\Exceptions\ValueException;

class ValueResolver extends AbstractResolver implements ValueResolverInterface
{
    public function execute($value, $strict = false)
    {
        /**
         * Trim comments
         */
        $result = $this->trimComments($value);

        /**
         * Trim spaces
         */
        $result = trim($result);

        /**
         * Check 'abc' or "abc"
         */
        $result = $this->checkInvalidSign($result);

        /**
         * Throw if failed one of ' or " sign
         */
        $result = $this->checkSurroundingStripes($result);

        /**
         * Trim empty '' or ""
         */
        $result = $this->trimEmpty($result);

        /**
         * Remove "" and ''
         * Fix it on TK3 crashed
         */
        $result = $this->trimClosers($result);

        /**
         * Remove new lines
         */
        $result = $this->trimNewLines($result);

        /**
         * Trim backslashes "\" sign
         */
        $result = $this->trimSlashes($result);

        /**
         * Parsing values
         */
        $result = $this->valueparser->parse($result, $strict);

        return $result;
    }

    private function trimSlashes($result)
    {
        $result =  preg_replace('/\\\\/', '\\', $result);

        return stripslashes($result);
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
        $parts = ['\'', '"'];

        foreach ($parts as $part) {
            if (substr($input, 0, 1) === $part && substr($input, -1) === $part) {
                return substr($input, 1, -1);
            }
        }

        return $input;
    }

    private function trimComments($input)
    {
        preg_match_all(self::EXPLODE_COMMENTS, $input, $matches, PREG_SET_ORDER, 0);

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
