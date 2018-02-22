<?php

namespace Codervio\Envmanager\Editor;

class Formatter
{
    protected $values = null;

    public function setKeys($item)
    {
        if (array_key_exists('key', $item)) {
            $key = $item['key'];
            $value = array_key_exists('value', $item) ? $item['value'] : null;
            $comment = array_key_exists('comment', $item) ? $item['comment'] : null;
            $export = array_key_exists('export', $item) ? $item['export'] : null;

            $packArray = compact('key', 'value', 'comment', 'export');

            $this->values = $packArray;
        }

        return $this->values;
    }

    public function formatSetter($value)
    {
        return (string)"{$value['export']}{$value['key']}={$value['value']}{$value['comment']}";
    }

    public function formatComment($comment)
    {
        return (string)'# '.$comment;
    }

    public function addEmptyLine()
    {
        return (string)PHP_EOL;
    }
}