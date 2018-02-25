# Envparser

An Envparser constructor

## Description

```php
Envparser($path = null, $keepComments = false, $extension = array('env', 'main.env'))
```

Reading a files of environment .env files from folder or .env defined files.
It is possible to read comments and parsing it.

By default path should be defined. By default it skips full line comments.
Extension by default reads .env or main.env files.

## Parameters

__path__
: A path of env files or directory
: Default: null

__keepComments__
: Keep comments readed from a file
: Default: `false`

__extension__
: Read specify extension
: Default: `env` or `main.env`

## Return values

__instance__
: Returns instance of readed env files

__Exception__
_No exception_

## Examples

Example #1 Read a main.env file
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser('main.env');
$envparser->load();
```

Example #2 Read a directory with .env extension only
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser(__DIR__, false, array('env');
$envparser->load();
```

Example #3 Don't read comments from main.env file
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser('main.env', true);
$envparser->load();
```

## Notes

> By default read only .env or main.env extension files. 

## See also

_No documents._
