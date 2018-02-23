<?php

namespace Codervio\Envmanager;

use Codervio\Envmanager\Prerequisities;
use Codervio\Envmanager\Loader\Loader;
use Codervio\Envmanager\Resolver\KeyResolver;
use Codervio\Envmanager\Resolver\ValueResolver;
use Codervio\Envmanager\Parser\ParserCollector;
use Codervio\Envmanager\Resolver\VariableResolver;
use Codervio\Envmanager\Parser\Parser;
use Exception;

/**
 * Class EnvParser
 *
 * @todo escape quotation mark
 * @todo new line into double quoted
 * @todo tests
 * @todo parse pseudo env
 * @todo multiline parsing
 */
class Envparser
{
    /**
     * @var array
     */
    public $lines = array();
    protected $context;
    protected $keepcomments;
    protected $valueresolver;
    protected $parsercollector;
    protected $variable_collector;
    protected $loader;
    protected $strictbool;
    protected $processEnv = true;
    protected $override = false;
    private $result;
    private $parsedsystemvars = array();

    protected $fileEncoding;

    public function __construct($path = null, $keepComments = false, $extension = array('env', 'main.env'))
    {
        new Prerequisities();

        $this->loader = new Loader($path, $extension);

        $this->parser = new Parser();
        $this->keepcomments = $keepComments;

        $this->keyresolver = new KeyResolver($this);
        $this->valueresolver = new ValueResolver($this);

        $this->parsercollector = new ParserCollector();

        $this->variable_collector = new VariableResolver();
    }

    public function setParse(array $var, $processEnvironment = true)
    {
        $this->processEnv = $processEnvironment;

        $this->context = $var;
    }

    public function load($processEnvironment = true, $override = false)
    {
        $this->processEnv = $processEnvironment;

        $this->override = $override;

        if ($this->loader->getLoadStatus()) {
            $this->context = $this->parseEncoding($this->loader->run());
            return true;
        }

        return false;
    }

    private function getParsed() : ParserCollector
    {
        $parsedText = $this->parser->prepareLines($this->context);

        $this->parser->explodeLines($parsedText);

        return $this->parseLines();
    }

    public function setEncoding($encoding = 'UTF-8')
    {
        $this->fileEncoding = $encoding;
    }

    private function parseEncoding($data)
    {
        if (empty($this->fileEncoding)) {
            $this->fileEncoding = mb_detect_encoding($data, "auto");
        }

        if (!in_array($this->fileEncoding, mb_list_encodings())) {
            throw new Exception(sprintf('Encoding "%s" not found. Check supported encoding using mb_list_encoding() function or use mostly UTF-8 encoding type', $this->fileEncoding));
        }

        return mb_convert_encoding($data, $this->fileEncoding, 'auto');
    }

    public function getEncoding()
    {
        return $this->fileEncoding;
    }

    public function setStrictBool($state = true)
    {
        $this->strictbool = $state;
    }

    public function require($variable)
    {
        // Validator

        $variableValidator = new VariableValidator($variable);

        $variableValidator->setStrict($this->strictbool);
        $variableValidator->setResult($this->result);

        return $variableValidator;

    }

    public function parseLines() : ParserCollector
    {
        foreach (getenv() as $key => $value)
        {
            $parsedsysvars = $this->parsercollector->offsetSet($key, $value);
        }

        $this->parsedsystemvars = $parsedsysvars;

        foreach ($this->parser->arr->getArrayCopy() as $key => $line) {

            if ($this->variable_collector->isComment($line) && !$this->keepcomments) {

                $this->parser->arr->offsetUnset($key);

            } else {

                list($k, $v) = $this->explodeKeyVal($line);

                $key = $this->keyresolver->execute($k);
                $value = $this->valueresolver->execute($v, $this->strictbool);

                $value = $this->variable_collector->parseSystemEnvironmentVariables($value);
                $value = $this->variable_collector->parseVariable($value, $this->parsercollector);

                $this->parsercollector->offsetSet($key, $value);
            }
        }

        unset($this->parser->arr);

        return $this->parsercollector;
    }

    public function checkSuperGlobalsSet()
    {
        if (ini_get('variables_order') === '' || strpos(ini_get('variables_order'), 'G')) {
            return true;
        } else {
            throw new RuntimeException('Warning: Set and create globals for $_ENV is disabled. To enable globally, for console run: \'php -d variables_order=EGPCS php.php\' or set in php.ini directive: variables_order=EGPCS');
        }
    }

    public function processEnvironment($values)
    {
        foreach ($values as $envkey => $envvalue) {
            // HTTP_ ....

            if (!$this->override) {

                if (function_exists('getenv')) {
                    if (!getenv($envkey, false)) {
                        putenv("$envkey=$envvalue");
                    }
                }

                if (!isset($_ENV[$envkey])) {
                    $_ENV[$envkey] = $envvalue;
                }

                if (!isset($_SERVER[$envkey])) {
                    $_SERVER[$envkey] = $envvalue;
                }

                if (function_exists('apache_getenv')) {
                    if (!apache_getenv($envkey, false)) {
                        @apache_setenv($envkey, $envvalue, false);
                    }
                }

            } else {

                if (function_exists('putenv')) {
                    putenv("$envkey=$envvalue");
                }
                $_ENV[$envkey] = $envvalue;
                $_SERVER[$envkey] = $envvalue;
                if (function_exists('apache_setenv')) {
                    @apache_setenv($envkey, $envvalue, false);
                }

            }
        }
    }

    public function getValue($value)
    {
        if (empty($value)) {
            throw new Exception('No value set');
        }

        if (function_exists('apache_getenv')) {
            if (apache_getenv($value, false)) {
                return apache_getenv($value, false);
            }
        }

        if (function_exists('getenv')) {
            if (getenv($value, false)) {
                return getenv($value);
            }
        }

        if (!isset($_ENV[$value])) {
            return $_ENV[$value];
        }
    }

    public function getSystemVars()
    {
        return $this->parsedsystemvars;
    }

    public function run()
    {
        if (!$this->loader->getLoadStatus()) {
            return $this->processEnvironment($this->context);
        }

        $result = $this->getParsed();

        $resultParsed = $result->getArrayCopy();

        if ($this->processEnv) {
            $this->processEnvironment($resultParsed);
        }

        $this->result = $resultParsed;

        return $resultParsed;
    }

    private function explodeKeyVal($value)
    {
        return explode('=', $value);
    }
}