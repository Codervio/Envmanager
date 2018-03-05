<?php

namespace Codervio\Envmanager\Resolver;

interface ValueResolverInterface
{
    const INVALID_PREG = '/(^(\')+[a-z]+$)|(^[a-z]+(\')$)|(^(\")+[a-z]+$)|(^[a-z]+(\")$)/u';
    const CLOSERS =  '/^[\'"](.*)[\'"]/i';
    const CLOSURE_MATCH = '/\"(.*)\s+(.*)\"/i';

    const EXPLODE_COMMENTS = '/(?=(^(.*))[ ](?=#[ a-z0-9]+))|(?=(^(.*))+)/i';
    const GET_COMMENT = '/( #)([ a-z0-9]+)/i';

    const DOS_NL = '\r\n';
    const UNIX_NL = '\n';
    const MAC_NL = '\n';
}