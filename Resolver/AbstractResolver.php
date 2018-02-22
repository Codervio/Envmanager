<?php

namespace Codervio\Envmanager\Resolver;

use Codervio\Envmanager\Parser\ValueParser;

abstract class AbstractResolver
{
    protected $keyresolver;

    protected $valueparser;

    public function __construct($resolver)
    {
        $this->keyresolver = $resolver;
        $this->valueparser = new ValueParser();
    }
}