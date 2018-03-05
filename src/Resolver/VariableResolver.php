<?php

namespace Codervio\Envmanager\Resolver;

use Codervio\Envmanager\Exceptions\ValueException;
use Exception;

class VariableResolver
{
    public function isComment($value = null)
    {
        return substr($value, 0, 1) === '#';
    }

    public function parseSystemEnvironmentVariables($value)
    {
        if (preg_match_all('#\$\{([a-zA-Z0-9]+)\}#', $value, $matches, PREG_SET_ORDER)) {

            if (is_array($matches)) {

                foreach ($matches as $match) {

                    $origin = $match[0];
                    $nameVar = $match[1];

                    if (getenv($nameVar)) {

                        $value = str_replace($origin, getenv($nameVar), $value);

                    }
                }

            }
        }

        return $value;
    }

    /**
     *
     * Check length on Windows OS Value
     *
     * Maximum size of environment value on Windows is 32,767 character length
     *
     * @param $value
     */
    public function checkLength($value)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            if (strlen($value) > 32767) {
                throw new ValueException('A value length on Windows OS family is limited to 32767 characters.');
            }
        }
    }

    public function parseVariable($value, $collector)
    {
        $this->checkLength($value);

        $collector = $collector->getArrayCopy();

        if (preg_match_all('#\$\{([a-zA-Z0-9]+)\}#', $value, $matches, PREG_SET_ORDER)) {

            if (is_array($matches)) {

                foreach ($matches as $match) {

                    $origin = $match[0];
                    $nameVar = $match[1];

                    if (isset($collector[$nameVar])) {

                        $value = str_replace($origin, $collector[$nameVar], $value);

                    } else {
                        throw new Exception(sprintf('Unknown %s variable. Please check environment "%s" variable', $origin, $nameVar));
                    }

                }

            }
        }

        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {

            if (preg_match_all('/\%([a-zA-Z0-9]+)\%/i', $value, $matches, PREG_SET_ORDER)) {

                if (is_array($matches)) {

                    foreach ($matches as $match) {

                        $origin = $match[0];
                        $nameVar = $match[1];

                        if (isset($collector[$nameVar])) {

                            $value = str_replace($origin, $collector[$nameVar], $value);

                        } else {
                            throw new Exception(sprintf('Unknown %s variable. Please check environment "%s" variable', $origin, $nameVar));
                        }

                    }

                }
            }

        }

        return $value;
    }
}