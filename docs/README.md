# Environment manager

The `Environment manager` parse, populates dot environment files to super global $_ENV files, HTTP request headers.

It provides for editing environment file and manipulate them.

## Installation

1. Installation via [Composer](http://www.composer.org) on [Packagist](http://www.packagist.com)
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

$envparser = new Envparser;
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

### EnvParser

* [`load()`](load.md) - Load an environment .env  file or folder with .env files

### EnvEditor