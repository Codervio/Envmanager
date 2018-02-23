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
         * Remove "" and ''
         * Fix it on TK3 crashed
         */
        //$result = $this->trimClosers($result);

        /**
         * Remove new lines
         */
        $result = $this->trimNewLines($result);

        /**
         * Parsing values
         */
        $result = $this->valueparser->parse($result, $strict);

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
        preg_match_all(self::CLOSERS, $input, $result, PREG_SET_ORDER, 0);

        return addslashes($result[0]);
    }

    private function trimComments($input)
    {
        return preg_replace('/#\S+[ \t]*/', '', $input);
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
