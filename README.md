# Environment manager

The `Environment manager` parse, populates dot environment files to super global $_ENV files, HTTP request headers.

It provides for editing environment file and manipulate them.

![Screenshot](https://github.com/Codervio/Envmanager/raw/master/screenshot.png "Screenshot")

## Installation

1. Installation via [Composer](http://www.composer.org) on [Packagist](https://packagist.org/packages/codervio/envmanager)
2. Installation using [Git](http://www.github.com) GIT clone component

## Prerequisities

PHP version requirements: _PHP >7.0_

Use `use Codervio\Environment\EnvParser` declaration for parsing dot env files.

Use `use Codervio\Environment\EnvEditor` declaration for edit and manage dot env file.

## Usage

Example of fetching env variables and loading into global super variables like `$_ENV`, using function `getenv()` or directly using instance: 

If on a file example.env contains a data: 

```text
FOO=bar
```

After loading instance it can be fetching a variable:

```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser('.env');
$envparser->load();
$envparser->run();

$result = $parser->getValue('FOO');
var_dump($result);
```

Returns a result will automatically detect type of getting env variables:

```php
(string) 'bar'
```

Or get a result using env variables globally:
```php
    echo apache_getenv('FOO')
    echo getenv('FOO')
    echo $_ENV('FOO')
```

## Returning a result

A result returns in following orders:
- using apache_getenv() if apache service is configured internally to use only Apache environment
- using getenv() that most Unix systems supports
- using PHP native super globals $_ENV function

It will automatically parse to PHP env functions and super globals so you can access via:
- superglobals functions $_ENV
- superglobals $_SERVER variable
- using getenv() if is enabled by system
- using apache_getenv() if is enabled by Apache service

## Changelog

Status of core:

| Version       | State                |
| ------------- |:-------------------- |
| `1.0`         | Release version      |

PHP version above `7.0`.
Quality assurance: Unit tests provided

## Table of Contents

### Envparser

#### Examples

* For loading simple ENV variables use [`example`](getvalue.md)

```text
FOO=bar
VAREMPTY=
FOO1=foo1
WITHSPACES="with spaces"
```

* Lists of examples env variables: [`lists`](lists.md)

* Writing comments: [`comments`](comments.md)

```shell
# comment
# a comment #comment
## A main comment ##
FOO=bar # a comment
```

It is possible to parse comments variables such using:

```php
 new Envparser('main.env', true);
```

A variable inside comment can be visible

```shell 
 #COM1=BAR1
```

using command:

```php
 $envparser->getAllValues(); # to get all values
 $envparser->getValue('#COM1'); # to get commented key
```

which returns as array and keeps # mark:

```shell
  ["#COM1"]=>
  string(4) "BAR1"
```

or directly:

```php
 $envparser->getValue('#COM1');
```

will parsing a variable

```shell
  string(4) "BAR1"
```

* Parsing export or setenv variables: [`envexports`](envexports.md)

```shell
setenv FOO1=value
export FOO2=value
```

#### References

* `Envparser()` - A construct parser constructor
* `load()` - Load an environment .env  file or folder with .env files
* `getValue()` - Get a value from system environment variables
* `getAllValues()` - Returns parsed environment variables internally as array

### EnvEditor