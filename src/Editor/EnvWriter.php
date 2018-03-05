<?php

namespace Codervio\Envmanager\Editor;

use Codervio\Envmanager\Resolver\ArrayStore;
use Codervio\Envmanager\Editor\Formatter;

class EnvWriter
{
    protected $buffer;

    protected $arr;

    protected $forceclear = false;

    public function __construct()
    {
        $this->arr = new ArrayStore();
        $this->formatter = new Formatter;
    }

    public function set($buffer)
    {
        if (is_null($buffer)) {
            return (string)$buffer;
        }

        $this->buffer = $buffer;
    }

    public function clearBuffer()
    {
        if (is_array($this->arr->getArrayCopy())) {
            foreach ($this->arr->getArrayCopy() as $index => $value) {
                $this->arr->offsetUnset($index);
            }
        }
    }

    public function clearAll()
    {
        $this->forceclear = true;
    }

    private function ensureForcedClear()
    {
        if ($this->forceclear) {
            unset($this->arr);
        }
    }

    public function get()
    {
        $this->ensureForcedClear();

        $result = '';

        if (empty($this->arr)) {
            return (string)null;
        }

        foreach ($this->arr->getArrayCopy() as $value) {

            if (is_null($value)) {
                $result .= $this->formatter->addEmptyLine();
            } else if (isset($value['comment']) && (!isset($value['key']))) {
                $result .= $this->formatter->formatComment($value['comment']).PHP_EOL;
            } else {
                $result .= $this->formatter->formatSetter($value).PHP_EOL;
            }
        }

        return $result;
    }

    public function put($key, $value = null, $comment = null, $export = false)
    {
        $packArray = compact('key', 'value', 'comment', 'export');

        $result = $this->formatter->setKeys($packArray);

        $this->arr->offsetSet($key, $result);
    }

    public function appendComment($comment)
    {
        $this->arr->append(array('comment' => $comment));
    }

    public function appendLine()
    {
        $this->arr->append(null);
    }

    public function remove($key)
    {
        if (!$this->arr->offsetExists($key)) {
            return false;
        }

        $this->arr->offsetUnset($key);
    }

    public function removeComment($removeKey)
    {
        $arrays = $this->arr->getArrayCopy();

        foreach ($arrays as $key => $value)
        {

            if (isset($value['comment'])) {

                if ($value['comment'] === $removeKey) {

                    $this->arr->offsetUnset($key);
                    return true;
                }
            }
        }

        return false;
    }
}