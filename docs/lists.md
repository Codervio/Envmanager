# List of example env values

On this chapter you can see real world example environment variables.

## Simple example values

```shell
FOO=bar
VAREMPTY=
VAREMPTYSTRING=""
VARWITHQUOTE='with quote value'
VARWITHQUOTEDOUBLEQUOTE="with quote value"
WITHOUTQUOTE=somestring
```

## Invalid values

```shell
FOO=foo bar
```

should be:

```shell
FOO="foo bar"
```

## Comments, a variable with comments

```shell
# comment
# a comment #comment
FOO1=foo1 #comment
VARSPACES="with space value" #comment
VARWITHQUOTE='with quote value' #a comment
COMMENTANDMARKS='a value with & mark and \" mark with # sign' # a comment

## A main comment ##
```

## Defined export or setenv

```shell
export FOO=foo bar
setenv FOO=foo bar
```

## Invalid variable

A key variable can not start with a number. Otherwise it will thrown ParserException()

```shell
2FOO=bar
```

```php
use Codervio\Envmanager\Exceptions\ParseException;

try {
    $parser->getValue('2FOO');
} catch ParserException($msg) {
    // $msg;
}
```

## Notes

_No notes._

## See also

_No documents._
