<?php

namespace Codervio\Envmanager;

use RuntimeException;

class Prerequisities
{
    public function __construct()
    {
        if (!extension_loaded('mbstring')) {
            throw new RuntimeException('Extension "mbstring" is not loaded. Please install ""');
        }
    }
}