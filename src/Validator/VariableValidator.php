<?php

namespace Codervio\Envmanager\Validator;

use Codervio\Envmanager\Parser\ValueParser;
use Codervio\Envmanager\Exceptions\ValidationException;
use Codervio\Envmanager\Exceptions\VariableException;
use Exception;

class VariableValidator
{
    protected $variable;

    protected $valueParser;

    private $result;

    private $strict = false;

    public function __construct($variable)
    {
        $this->variable = $variable;

        $this->valueParser = new ValueParser;
    }

    public function setResult($result)
    {
        $this->result = $result;

        $this->checkVariable($this->variable, $this->result);
    }

    private function checkVariable($variable, $result)
    {
        if (!array_key_exists($variable, $result)) {
            throw new VariableException(sprintf('No variable "%s" set', $variable));
        }
    }

    public function setStrict($strict)
    {
        $this->strict = $strict;
    }

    public function isBoolean()
    {
        if (!isset($this->result[$this->variable])) {
            throw new Exception('No variable');
        }

        if (is_bool($this->valueParser->parseBool($this->result[$this->variable], $this->strict))) {
            return true;
        }

        return false;
    }

    public function isEmpty()
    {
        if (empty($this->valueParser->parseEmpty($this->result[$this->variable]))) {
            return true;
        }

        return false;
    }

    public function isNumber()
    {
        if (is_int($this->valueParser->parseNumeric($this->result[$this->variable]))) {
            return true;
        }

        return false;
    }

    public function isFloat()
    {
        if (is_float($this->valueParser->parseFloat($this->result[$this->variable]))) {
            return true;
        }

        return false;
    }

    public function checkValues($value)
    {
        if (!isset($this->result[$this->variable])) {
            throw new ValidationException('No variable');
        }

        if (is_array($value)) {

            $arraydiffs = array_diff($value, array($this->result[$this->variable]));

            if (is_array($arraydiffs) && !empty($arraydiffs)) {
                throw new ValidationException(sprintf('Missing value: "%s" in "%s" environment variable', join(', ', $arraydiffs), $this->variable));
            }

            return true;
        }

        if ($this->result[$this->variable] === $value) {
            return true;
        }

        return false;
    }
}