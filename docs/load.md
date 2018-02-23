# load

Load an environment .env  file or folder with .env files

## Description

```php
load($processEnvironment = true, $override = false)
```

Load and env file or files inside folders.
ProcessEnvironment parses a variable and populate into ENV system variables.
If a variable exists on system from .env file it can override value.

By default it will load and automatically detect encoding type.

## Parameters

__processEnvironment__
: Parse into system env variable from a file
: Default: `true`

__override__
: Replace a value into environment system variable
: Default: `false`

## Return values

__bool__
: Returns `TRUE` if loaded a file or `FALSE` if is not loaded a file

__Exception__
1. Exception on `mb_string` encoding if not defined default mb_string values

## Examples

Example #1 Load a file
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser('.env');
$envparser->load();
```

Example #2 Load a file from directory
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser(__DIR__);
$envparser->load();
```

Example #3 Load a file and parse into ENV variable, don't override a variable into ENV system variable
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser(__DIR__);
$envparser->load(true, false);
```

Example #4 Don't parse into global ENV variable, don't override a variable into ENV system variable 
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser(__DIR__);
$envparser->load(false);
```

## Notes

> This will not run ENV parser. It will only load a files. To run use `run()` function after `load()` a function that loaded a file or files.

## See also

_No documents._
