<?php

namespace Codervio\Http\Tests;

use Codervio\Envmanager\Envparser;

/**
 * Envparser unit test
 * 
 */
class EnvparserTest extends \PHPUnit_Framework_TestCase
{
    public $tmpPathEnv;
    public $instance;

    const DS = DIRECTORY_SEPARATOR;

    protected function setUp()
    {
        $this->tmpPathEnv = sys_get_temp_dir().self::DS.'EvnmanagerTestEnv.env';

        if (!touch($this->tmpPathEnv)) {
            throw new \RuntimeException(sprintf('A test failed. Could not write a file to "%s" path', $this->tmpPathEnv));
        }

        $tmpData = <<<EOF
FOO=bar
EOF;

        file_put_contents($this->tmpPathEnv, $tmpData);
    }

    public function testInstance()
    {
        $this->instance = new Envparser;
        $instanceClass = $this->instance instanceof Envparser;

        $this->assertEquals($instanceClass, true);
    }

    private function getFileTest($envFile)
    {
        return __DIR__.self::DS.'fixtures'.self::DS.$envFile;
    }

    public function testRead()
    {
        $envfile = $this->getFileTest('.env');

        $this->assertTrue(is_readable($envfile));
    }

    public function testEnvLoadsFile()
    {
        $envfile = $this->getFileTest('.env');

        $envparser = new Envparser($envfile);
        $envparserLoader = $envparser->load();

        $this->assertTrue($envparserLoader);
    }

    public function testEnvVar()
    {
        $envfile = $this->getFileTest('.env');

        $envparser = new Envparser($envfile);
        $envparserLoader = $envparser->load();

        $envparser->run();

        $envVar = $envparser->getValue('FOO');

        $this->assertSame($envVar, 'bar');
        $this->assertSame($envVar, getenv('FOO'));
    }
}
