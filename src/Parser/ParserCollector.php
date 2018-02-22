<?php

namespace Codervio\Envmanager\Parser;

use ArrayObject;

class ParserCollector extends ArrayObject
{
    public function __construct($input = array(), int $flags = 0, string $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
    }
}