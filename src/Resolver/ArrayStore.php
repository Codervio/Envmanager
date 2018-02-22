<?php

namespace Codervio\Envmanager\Resolver;

use ArrayObject;

class ArrayStore extends ArrayObject
{
    public function __construct($input = array(), $flags = 0, $iterator_class = "ArrayIterator")
    {
        parent::__construct($input, $flags, $iterator_class);
    }
}