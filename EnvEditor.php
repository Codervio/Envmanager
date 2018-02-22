<?php

namespace Codervio\Envmanager;

class EnvEditor extends EnvParser
{
    private $content;

    protected $writer;
    protected $reader;
    protected $context;

    public function __construct($context)
    {
        new Prerequisities();

        $this->writer = new EnvWriter();

        if ($context instanceof EnvParser) {
            // $this->content = $content;

            if ($context->loader->getLoadStatus()) {

                $this->context = $context->loader->getFile();

                $parsedValues = $context->parsercollector->getArrayCopy();

                foreach ($parsedValues as $parsedKey => $parsedValue) {

                    $this->writer->put($parsedKey, $parsedValue);

                }
            }


        } else {

            $this->context = $context;

            $loader = new Loader($context, array('env', 'main.env'));

            $content = $loader->run();

            if (!$content) {
                return null;
            }

            $this->content = $content;
        }
    }

    public function addEmptyLine()
    {
        $this->writer->appendLine();
    }

    public function persist($key, $value = null, $comment = null, $export = false)
    {
        $this->writer->put($key, $value, $comment, $export);
    }

    public function addComment($comment = null)
    {
        $this->writer->appendComment($comment);
    }

    public function removeComment($value)
    {
        $this->writer->removeComment($value);
    }

    public function remove($key)
    {
        $this->writer->remove($key);
    }

    public function save()
    {
        return file_put_contents($this->context, $this->getContent());
    }

    public function removeFile()
    {
        unlink($this->context);
    }

    public function getContent()
    {
        return (string)$this->writer->get();
    }

    public function clearContent()
    {
        $this->writer->clearBuffer();
    }

    public function forceClear()
    {
        $this->writer->clearAll();
    }

}