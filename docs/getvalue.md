# getValue

Get a value from system environment variables

## Description

```php
getValue($env_variable)
```

Get and environment variable in next ordering:
- if is null returns null
- get a variable from apache_getenv
- get a from system getenv
- get from super globals $_ENV

It will get a value automatically from env variables so you don't need manually like getenv() function.

If you prefer native calls function use:
- getenv()
- apache_getenv()
- $_ENV

## Parameters

__variable__
: A string variable
: A value inside quote ' or " value with spaces

## Return values

__string__
: Returns value of environment variable

__null__
: Returns a null if not variable set

__Exception__
1. todo

## Examples

Example #1 Get a simple value

A '.env' file:
```text
FOO=bar
VAREMPTY=
FOO1=foo1
```

A sample reading '.env' file:

```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser('.env');
$envparserLoader = $envparser->load();

$envparser->run();

# Autodetected environment variable
echo $envparser->getValue('FOO'); 

# Manually native environment function called
echo getenv('FOO1');
```

Returns:

```text
(string) 'bar'
(string) 'foo1'
```

Example #2 Get a value that not exists a value

A '.env' file:
```text
VAREMPTY=
```

A sample usage:
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser('.env');
$envparserLoader = $envparser->load();

$envparser->run();

echo $envparser->getValue('VAREMPTY'); 
```

Returns:

```text
(bool) null
```

Some other examples:

```text
VARSPACES="with space value" #comment
VARWITHQUOTE='with quote value'
```

Will return:

```text
(string) with space value
(string) with quote value
```

## Notes

> If a value is not defined it will return null value
> A value with spaces without quote is forbidden such as 'VAR=some value'. Should be: 'VAR="some value"' 

## See also

* [`lists`](lists.md) - Example env variables
* [`getAllValues()`](getallvalues.md) - Returns parsed environment variables internally as array