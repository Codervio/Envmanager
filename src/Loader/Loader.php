<?php

namespace Codervio\Envmanager\Loader;

use Exception;
use RuntimeException;

class Loader
{
    protected $filepath;
    protected $isloaded = false;
    protected $extension;
    protected $strict = false;

    public function __construct($path, $extension)
    {
        $this->filepath = $path;
        $this->extension = $extension;
    }

    public function getLoadStatus()
    {
        return !empty($this->filepath);
    }

    public function getFile()
    {
        return $this->filepath;
    }

    public function run()
    {
        $this->isloaded = true;

        $content = null;

        $path = glob($this->filepath);

        if (!$path) {
            $this->isloaded = false;
            return false;
        }

        foreach ($path as $filemain)
        {
            $fileInfo = pathinfo($filemain);

            $pathInfo = $fileInfo['dirname'].DIRECTORY_SEPARATOR.$fileInfo['basename'];

            if (is_dir($pathInfo)) {
                $dirlist = @scandir($pathInfo);

                foreach ($dirlist as $file) {

                    $fileInfoFolder = pathinfo($file);

                    $pathInfoFolder = $fileInfoFolder['dirname'].DIRECTORY_SEPARATOR.$fileInfoFolder['basename'];

                    if ($this->isReadable($path)) {

                        if (in_array($fileInfoFolder['extension'], $this->extension)) {

                            $content .= file_get_contents($pathInfoFolder);

                        } else {

                            throw new Exception('Invalid extension');

                        }

                    }

                }

            } else {

                if (is_readable($pathInfo)) {

                    $content .= file_get_contents($pathInfo);
                }

            }

        }

        return $content;
    }

    private function isReadable($path)
    {
        if (is_file($path) || is_readable($path)) {
            return true;
        }

        throw new RuntimeException(sprintf('A file %s is not readable or not exists'. $path));
    }
}
