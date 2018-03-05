<?php

namespace Codervio\Envmanager\Abstractparser;

use Codervio\Envmanager\Parser\ParserCollector;

abstract class Envabstract
{
    protected $fileEncoding;
    protected $parsedsystemvars = array();
    protected $commentResolver = array();

    protected function parseEncoding($data)
    {
        if (empty($this->fileEncoding)) {
            $this->fileEncoding = mb_detect_encoding($data, "auto");
        }

        if (!in_array($this->fileEncoding, mb_list_encodings())) {
            throw new \Exception(sprintf('Encoding "%s" not found. Check supported encoding using mb_list_encoding() function or use mostly UTF-8 encoding type', $this->fileEncoding));
        }

        return mb_convert_encoding($data, $this->fileEncoding, 'auto');
    }

    protected function explodeKeyVal($value)
    {
        $keyPair = strtok($value, '=');

        preg_match("/^([^=]*)=(.*)/i", $value, $matches, 0);

        if (!isset($matches[2])) {
            return array($keyPair, null);
        }

        return array($keyPair, $matches[2]);
    }

    protected function processEnvironment($values)
    {
        if (!is_array($values)) {
            return null;
        }

        foreach ($values as $envkey => $envvalue) {
            // HTTP_ ....

            if (!$this->override) {

                if (!isset($_ENV[$envkey])) {
                    $_ENV[$envkey] = $envvalue;
                }

                if (function_exists('getenv')) {
                    if (!getenv($envkey, false)) {
                        putenv("$envkey=$envvalue");
                    }
                }

                if (!isset($_SERVER[$envkey])) {
                    $_SERVER[$envkey] = $envvalue;
                }

                if (function_exists('apache_getenv')) {
                    if (!apache_getenv($envkey, false)) {
                        /** @scrutinizer ignore-unhandled */ @apache_setenv($envkey, $envvalue, false);
                    }
                }

            } else {

                $_ENV[$envkey] = $envvalue;
                if (function_exists('putenv')) {
                    putenv("$envkey=$envvalue");
                }

                $_SERVER[$envkey] = $envvalue;
                if (function_exists('apache_setenv')) {
                    /** @scrutinizer ignore-unhandled */ @apache_setenv($envkey, $envvalue, false);
                }

            }
        }
    }

    protected function parseLines() : ParserCollector
    {
        foreach (getenv() as $key => $value)
        {
            $this->parsedsystemvars = $this->parsercollector->offsetSet($key, $value);
        }

        if (empty($this->parser->arr->getArrayCopy())) {
            return $this->parsercollector;
        }

        foreach ($this->parser->arr->getArrayCopy() as $key => $line) {

            if ($this->variable_collector->isComment($line) && !$this->keepcomments) {

                $this->parser->arr->offsetUnset($key);

            } else {

                list($k, $v) = $this->explodeKeyVal($line);

                $key = $this->keyresolver->execute($k);
                $value = $this->valueresolver->execute($v, $this->getStrictBool());

                $this->commentResolver[$key] = $this->valueresolver->resolveComment($v);

                $value = $this->variable_collector->parseSystemEnvironmentVariables($value);
                $value = $this->variable_collector->parseVariable($value, $this->parsercollector);

                $this->parsercollector->offsetSet($key, $value);
            }
        }

        unset($this->parser->arr);

        return $this->parsercollector;
    }

    protected function getStrictBool()
    {
        return $this->strictbool;
    }

    protected function getParsed() : ParserCollector
    {
        $parsedText = $this->parser->prepareLines($this->context);

        $this->parser->explodeLines($parsedText);

        return $this->parseLines();
    }
}