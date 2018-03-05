<?php

// https://github.com/m1/Env/blob/master/src/Parser/ValueParser.php

namespace Codervio\Envmanager\Parser;

use Codervio\Envmanager\Resolver\ArrayStore;

class Parser
{
    public $arr;
    private $data;

    public function __construct()
    {
        $this->arr = new ArrayStore();
    }

    public function prepareLines($input)
    {
        return str_replace(array('\n', '\r\n', '\r'), PHP_EOL, $input);
    }

    public function explodeLines($data)
    {
        $this->data = explode("\n", str_replace(array("\r\n", "\n\r", "\r"), "\n", $data));

        if (is_array($this->data)) {

            foreach ($this->data as $key => $val) {

                $this->arr->append($val);

                $this->cleanLine($key, $val);

            }
        } else {
            return;
        }
    }

    private function cleanLine($key, $line)
    {
        if (empty($line)) {
            $this->arr->offsetUnset($key);
        }
    }
}