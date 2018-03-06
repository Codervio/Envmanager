<?php

namespace Codervio\Http\Tests;

use Codervio\Envmanager\Envparser;
use Codervio\Envmanager\Exceptions\ValueException;
use Codervio\Envmanager\Exceptions\ParseException;
use Codervio\Envmanager\Exceptions\ValidationException;

if (!class_exists('\PHPUnit\Framework\TestCase') &&
    class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias('\PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

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
        $envparser->load();

        $envparser->run();

        $this->assertSame('bar', $envparser->getValue('FOO'));
        $this->assertSame($envparser->getValue('FOO1'), getenv('FOO1'));
        $this->assertEmpty($envparser->getValue('VAREMPTY'));
        $this->assertEmpty($envparser->getValue('NOTEXISTSVARIABLE'));
        $this->assertEmpty($envparser->getValue('VAREMPTYSTRING'));
        $this->assertSame('with space value', $envparser->getValue('VARSPACES'));
        $this->assertSame('with quote value', $envparser->getValue('VARWITHQUOTE'));
    }

    public function testEnvComments()
    {
        $envfile = $this->getFileTest('comments.env');

        $envparser = new Envparser($envfile);
        $envparser->load();

        $envparser->run();

        $this->assertSame($envparser->getValue('COMMENT'), 'BAR');
        $this->assertFalse(getenv('COM1'));
        $this->assertFalse(getenv('COM2'));
        $this->assertFalse(getenv('COM3'));
        $this->assertSame('with spaces', getenv('COM4'));
        $this->assertSame('with # spaces and hash', getenv('COM5'));
        $this->assertSame('with spaces', getenv('COM6'));
        $this->assertSame('with spaces', getenv('COM7'));
        $this->assertSame('with spaces', getenv('COM8'));
        $this->assertSame('with spaces', getenv('COM9'));
        $this->assertSame('with spaces', getenv('COM10'));
        $this->assertSame('with spaces', getenv('COM11'));
        $this->assertSame('with tab spaced', getenv('COM12'));
        $this->assertSame("test with ' inside", getenv('COM13'));
        $this->assertSame('with spaces', getenv('COM10'));
        $this->assertSame('a value with & mark and " mark with # sign', getenv('COM14'));
        $this->assertEmpty(getenv('COM15'));
    }

    public function testEnvSpecial()
    {
        $envfile = $this->getFileTest('special.env');

        $envparser = new Envparser($envfile);
        $envparser->load();

        $envparser->run();

        $this->assertSame('mysql:host=localhost;dbname=db_name;charset=utf-8', getenv('SPEC1'));
        $this->assertSame('mysql:host=localhost;dbname=db_name;', getenv('SPEC2'));
        $this->assertSame('test qoute " mark', getenv('SPEC3'));
        $this->assertSame('test backslash \\', getenv('SPEC4'));
        $this->assertSame('test backslash [\\]', getenv('SPEC5'));
    }

    /**
     * @expectedException Codervio\Envmanager\Exceptions\ValueException
     */
    public function testAssertException()
    {
        $envfile = $this->getFileTest('exception.env');

        $envparser = new Envparser($envfile);
        $envparser->load();

        $envparser->run();

        getenv('EXC1');
    }

    public function testEnvExportsSetenv()
    {
        $envfile = $this->getFileTest('export_setenv.env');

        $envparser = new Envparser($envfile);
        $envparser->load();

        $envparser->run();

        $this->assertSame('bar', getenv('EX1'));
        $this->assertSame('bar', getenv('EX2'));
        $this->assertSame('with spaces', getenv('EX3'));
        $this->assertEmpty(getenv('EX4'));

        $this->assertSame('bar', getenv('SETENV1'));
        $this->assertSame('bar', getenv('SETENV2'));
        $this->assertSame('with spaces', getenv('SETENV3'));
        $this->assertEmpty(getenv('SETENV4'));
    }

    /**
     * @expectedException Codervio\Envmanager\Exceptions\ParseException
     */
    public function testAssertExceptionVariable()
    {
        $envfile = $this->getFileTest('invalidvar.env');

        $envparser = new Envparser($envfile);
        $envparser->load();

        $envparser->run();

        getenv('2FOO');
    }

    public function testEnvExportsServerVariables()
    {
        $envfile = $this->getFileTest('export_setenv.env');

        $envparser = new Envparser($envfile);
        $envparser->load();

        $envparser->run();

        $this->assertSame('bar', $_SERVER['EX1']);
        $this->assertSame('bar', $_SERVER['EX2']);
        $this->assertSame('with spaces', $_SERVER['EX3']);
        $this->assertEmpty($_SERVER['EX4']);

        $this->assertSame('bar', $_SERVER['SETENV1']);
        $this->assertSame('bar', $_SERVER['SETENV2']);
        $this->assertSame('with spaces', $_SERVER['SETENV3']);
        $this->assertEmpty($_SERVER['SETENV4']);
    }

    public function testEnvExportsEnvVariables()
    {
        $envfile = $this->getFileTest('export_setenv.env');

        $envparser = new Envparser($envfile);
        $envparser->load();

        $envparser->run();

        $this->assertSame('bar', $_ENV['EX1']);
        $this->assertSame('bar', $_ENV['EX2']);
        $this->assertSame('with spaces', $_ENV['EX3']);
        $this->assertEmpty($_ENV['EX4']);

        $this->assertSame('bar', $_ENV['SETENV1']);
        $this->assertSame('bar', $_ENV['SETENV2']);
        $this->assertSame('with spaces', $_ENV['SETENV3']);
        $this->assertEmpty($_ENV['SETENV4']);

        $this->assertSame('bar', $_ENV['SETENV5']);
    }

    public function testParsingCommentsAndAllValues()
    {
        $envfile = $this->getFileTest('comments.env');

        $envparser = new Envparser($envfile, true);
        $envparser->load();

        $envparser->run();

        $parsers = $envparser->getAllValues();

        $this->assertSame('BAR2', $parsers['# COM2']);
        $this->assertSame('BAR1', $envparser->getValue('#COM1'));
    }

    public function testGetSystemVars()
    {
        $envparser = new Envparser();
        $envparser->load();
        $envparser->run();

        $systemvars = $envparser->getSystemVars();

        $this->assertSame($systemvars['USERNAME'], $_ENV['USERNAME']);
        $this->assertSame($envparser->getSystemVars('USERNAME'), $_ENV['USERNAME']);
        $this->assertTrue($envparser->checkSystemVar('USERNAME'));
    }

    public function testStrictBool()
    {
        $envfile = $this->getFileTest('bool_strict.env');

        $envparser = new Envparser($envfile, true);
        $envparser->setStrictBool(false);
        $envparser->load();
        $envparser->run();

        $this->assertSame('y', $envparser->getValue('STRICTVARY_ONLYVAR'));
        $this->assertSame('n', $envparser->getValue('STRICTVARN_ONLYVAR'));

        $this->assertTrue($envparser->getValue('BOOLYES'));
        $this->assertFalse($envparser->getValue('BOOLFALSE'));
        $this->assertTrue($envparser->getValue('BOOLTRUE'));
        $this->assertFalse($envparser->getValue('BOOLFALSE'));
        $this->assertTrue($envparser->getValue('BOOLON'));
        $this->assertFalse($envparser->getValue('BOOLOFF'));
    }

    public function testBoolVars()
    {
        unset($_ENV['STRICTVARY']);
        putenv("STRICTVARY=");

        $envfile = $this->getFileTest('bool_strict.env');

        $envparser = new Envparser($envfile, false);
        $envparser->setStrictBool(true);
        $envparser->load();
        $envparser->run();

        $this->assertTrue($envparser->getValue('STRICTVARY'));
    }

    public function testRequire()
    {
        $envfile = $this->getFileTest('checkvars.env');

        $envparser = new Envparser($envfile, false);
        $envparser->load();
        $envparser->run();

        $this->assertTrue($envparser->required('CVFOOEMPTY')->isEmpty());
        $this->assertFalse($envparser->required('CVFOONOTEMPTY')->isEmpty());

        $this->assertTrue($envparser->required('CVBOOL')->isBoolean());
        $this->assertTrue($envparser->required('CVBOOL2')->isBoolean());
        $this->assertTrue($envparser->required('CVBOOL3')->isBoolean());
        $this->assertFalse($envparser->required('CVBOOL4')->isBoolean());

        $this->assertTrue($envparser->required('CVNUMBER')->isNumber());
        $this->assertFalse($envparser->required('CVNUMBER2')->isNumber());
        $this->assertTrue($envparser->required('CVNUMBER3')->isFloat());
        $this->assertFalse($envparser->required('CVNUMBER2')->isFloat());
    }

    public function testCheckValuesValidator()
    {
        $envfile = $this->getFileTest('checkvars.env');

        $envparser = new Envparser($envfile, false);
        $envparser->load();
        $envparser->run();

        $this->assertTrue($envparser->required('CVFOONOTEMPTY')->checkValues('notempty'));
        $this->assertTrue($envparser->required('CVFOONOTEMPTY')->checkValues(array('notempty')));
    }

    /**
     * @expectedException Codervio\Envmanager\Exceptions\ValidationException
     */
    public function testCheckValuesValidationException()
    {
        $envfile = $this->getFileTest('checkvars.env');

        $envparser = new Envparser($envfile, false);
        $envparser->load();
        $envparser->run();

        $this->assertTrue($envparser->required('CVFOONOTEMPTY')->checkValues(array('notempty', 'abc')));
    }

    public function testVariables()
    {
        $envfile = $this->getFileTest('variables.env');

        $envparser = new Envparser($envfile, false);
        $envparser->load();
        $envparser->run();

        $this->assertSame(123, $envparser->getValue('MYVARTEST'));
        $this->assertSame("123", $envparser->getValue('MYVARTEST2'));
        $this->assertSame("0123", $envparser->getValue('MYVARTEST3'));
        $this->assertSame("123456", $envparser->getValue('MYVARTEST4'));
    }

    public function testSpecialCharacters()
    {
        $envfile = $this->getFileTest('special.env');

        $envparser = new Envparser($envfile, false);
        $envparser->load();
        $envparser->run();

        $this->assertSame("0.k$", $envparser->getValue('SP.EC6'));
        $this->assertSame('!@@$54#b5f&(*(#5FDS/?"{fdsfs3)fsdf__4+}|?"><a', $envparser->getValue('SPEC7'));
        $this->assertSame('������', $envparser->getValue('SPEC8'));
        $this->assertSame('�����������������������������', $envparser->getValue('SPEC9'));
    }

    public function testGetComment()
    {
        $envfile = $this->getFileTest('.env');

        $envparser = new Envparser($envfile, false);
        $envparser->load();
        $envparser->run();

        $this->assertSame('comment', $envparser->getComment('FOO1'));
    }
}
