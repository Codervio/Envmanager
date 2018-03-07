<?php

namespace Codervio\Envmanager;

use Codervio\Envmanager\Loader\Loader;
use Codervio\Envmanager\Resolver\KeyResolver;
use Codervio\Envmanager\Resolver\ValueResolver;
use Codervio\Envmanager\Parser\ParserCollector;
use Codervio\Envmanager\Resolver\VariableResolver;
use Codervio\Envmanager\Parser\Parser;
use Codervio\Envmanager\Parser\SystemParser;
use Codervio\Envmanager\Validator\VariableValidator;
use Codervio\Envmanager\Abstractparser\Envabstract;
use Exception;

/**
 * Class EnvParser
 *
 */
class Envparser extends Envabstract
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
    protected $strictbool = false;
    protected $processEnv = true;
    protected $override = false;

    protected $systemparser = array();
    protected $keyresolver;
    protected $parser;

    private $result;

    public function __construct($path = null, $keepComments = false, $extension = array('env', 'main.env'))
    {
        new Prerequisities();

        $this->systemparser = new SystemParser();

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

    public function setEncoding($encoding = 'UTF-8')
    {
        $this->fileEncoding = $encoding;
    }

    public function getEncoding()
    {
        return $this->fileEncoding;
    }

    public function setStrictBool($state = false)
    {
        $this->strictbool = $state;
    }

    public function required($variable)
    {
        $variableValidator = new VariableValidator($variable);

        $variableValidator->setStrict($this->getStrictBool());
        $variableValidator->setResult($this->result);

        return $variableValidator;
    }

    public function checkSuperGlobalsSet()
    {
        if (ini_get('variables_order') === '' || strpos(ini_get('variables_order'), 'G')) {
            return true;
        } else {
            throw new \RuntimeException('Warning: Set and create globals for $_ENV is disabled. To enable globally, for console run: \'php -d variables_order=EGPCS php.php\' or set in php.ini directive: variables_order=EGPCS');
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

        if (isset($_ENV[$value])) {
            return $_ENV[$value];
        }

        if (function_exists('getenv')) {
            if (getenv($value, false)) {
                return getenv($value);
            }
        }

        return null;
    }

    public function getAllValues()
    {
        return $this->parsercollector->getArrayCopy();
    }

    public function checkSystemVar($variable)
    {
        return $this->systemparser->checkValue($variable);
    }

    public function getSystemVars($variable = null)
    {
        $systemparse = $this->systemparser;
        return $systemparse::getValues($variable);
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

    public function getComment($key)
    {
        if (isset($this->commentResolver[$key])) {
            return $this->commentResolver[$key];
        }
    }
}