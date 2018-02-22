<?php

namespace Codervio\Envmanager\Resolver;

use Codervio\Envmanager\Exceptions\ParseException;

class KeyResolver extends AbstractResolver
{
    /**
     * Unix ksh, bash, bourne shells
     */
    const REG_EXPORT = '/export/';

    /**
     * Unix CSH shells
     */
    const REG_SETENV = '/setenv/';

    public function execute($value)
    {
        if (is_numeric(substr($value, 0, 1))) {
            throw new ParseException("Numeric should not start with number: %s", $value);
        }

        $value = preg_replace(self::REG_EXPORT, null, $value);
        $value = preg_replace(self::REG_EXPORT, null, $value);

        return trim($value);
    }
}