# getAllValues

Returns parsed environment variables internally as array

## Explanation

By default, a comment not parsed as variable.

```shell
Envparser('main.env', false);
```

For testing variables in a files and force to get comments values, you can get a values inside comment.

```shell
Envparser('main.env', true);
```
Warning: Use this method only for testing only to fetch all variables inside comments. 

## Examples

An env file:

```shell
#COM1=BAR1
# COM2=BAR2
# COM3=BAR3
```

A PHP function with enabled:

```shell
$envparser = new Envparser('main.env', true);
$envparser->load();
$envparser->run();

var_dump($envparser->getAllValues());

Will return:

```shell
  ["#COM1"]=>
  string(4) "BAR1"
  ["# COM2"]=>
  string(4) "BAR2"
  ["# COM3"]=>
  string(4) "FOO3"
```

With turned off keeping comment:

```php
$envparser = new Envparser('main.env');
```

or

```php
$envparser = new Envparser('main.env', true);, false);
```

```shell
 no results
```

## Notes

_No notes._

## See also

* [`getValue()`](getvalue.md) - Get a value from system environment variables
