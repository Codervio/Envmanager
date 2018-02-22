<?php

namespace Codervio\Envmanager\Resolver;

interface ValueResolverInterface
{
    const INVALID_PREG = '/(^(\')+[a-z]+$)|(^[a-z]+(\')$)|(^(\")+[a-z]+$)|(^[a-z]+(\")$)/mu';
    const CLOSERS =  '/^[\'"](.*)[\'"]/i';
    const CLOSURE_MATCH = '/\"(.*)\s+(.*)\"/i';

    const DOS_NL = '\r\n';
    const UNIX_NL = '\n';
    const MAC_NL = '\n';
}