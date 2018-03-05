<?php

namespace Codervio\Http\Tests;

use Codervio\Envmanager\Enveditor;

/**
 * Envparser unit test
 *
 */
class EnveditorTest extends \PHPUnit_Framework_TestCase
{
    public $tmpPathEnv;
    public $instance;

    const DS = DIRECTORY_SEPARATOR;

    protected function setUp()
    {
        $this->tmpPathEnv = sys_get_temp_dir() . self::DS . 'EnveditorTestEnv.env';

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
        $this->instance = new Enveditor($this->tmpPathEnv);
        $instanceClass = $this->instance instanceof EnvEditor;

        $this->assertEquals($instanceClass, true);
    }

    public function testWrite()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->persist('FOO2', 'valuetest', 'mycomment', true);

        $this->instance->save();

        $this->assertTrue($this->instance->save());

    }

    public function testContent()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->persist('FOO2', 'valuetest', 'mycomment', true);

        $this->instance->save();

        $content = <<<EOF
export FOO2="valuetest" # mycomment

EOF;

        $this->assertSame($content, $this->instance->getContent());
    }

    public function testComment()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->persist('FOO2', 'valuetest', 'mycomment', true);

        $this->instance->addComment('my comment');

        $this->instance->save();

        $content = <<<EOF
export FOO2="valuetest" # mycomment
# my comment

EOF;

        $this->assertSame($content, $this->instance->getContent());
    }

    public function testEmptyline()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->persist('FOO2', 'valuetest', 'mycomment', true);

        $this->instance->addEmptyLine();

        $this->instance->addComment('my comment');

        $this->instance->save();

        $content = <<<EOF
export FOO2="valuetest" # mycomment

# my comment

EOF;

        $this->assertSame($content, $this->instance->getContent());
    }

    public function testRemoveComment()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->addComment('my comment');
        $this->instance->addComment('new test');
        $this->instance->removeComment('my comment');

        $this->instance->save();

        $content = <<<EOF
# new test

EOF;

        $this->assertSame($content, $this->instance->getContent());
    }

    public function testRemoveVariable()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->persist('FOO3', 'fsdf', 'sfdsdfsdf', false);
        $this->instance->persist('FOO4', 'sdfsfsf', 'fsfsfs', false);
        $this->instance->persist('FOO5', 'fsdfrwfssdds', null, true);

        $this->instance->remove('FOO4');

        $this->instance->save();

        $content = <<<EOF
FOO3="fsdf" # sfdsdfsdf
export FOO5="fsdfrwfssdds"

EOF;

        $this->assertSame($content, $this->instance->getContent());
    }

    public function testRemoveFile()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->persist('FOO3', 'fsdf', 'sfdsdfsdf', false);

        $this->instance->save();

        $this->instance->removeFile();

        $this->assertFalse(is_file($this->tmpPathEnv));
    }

    public function testClearContent()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);

        $this->instance->addComment('1');
        $this->instance->addComment('2');
        $this->instance->clearContent();
        $this->instance->addComment('3');

        $this->instance->save();

        $content = <<<EOF
# 3

EOF;

        $this->assertSame($content, $this->instance->getContent());
    }

    public function testForceClear()
    {
        $this->instance = new Enveditor($this->tmpPathEnv);
        $this->instance->forceClear();
        $this->instance->addComment('1');
        $this->instance->save();

        $content = '';

        $this->assertSame($content, $this->instance->getContent());
    }
}