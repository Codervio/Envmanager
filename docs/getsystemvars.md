# getsystemvars

Return a system values and keys.

## Description

```php
getsystemvars($key = null)
getsystemvars()
```

Get from $_ENV, getenv or apache_getenv() system initial variables.
Fetch by a key or get an array results.

## Parameters

__key__
: Find by a key of system variable
: Default: null

## Return values

__string__
: Returns a value by key defined

__array__
: If is not defined key it will returns all array result

__null__
: Returns null if is not found by a key

## Examples

Get all environment system variables:

```php
    $envparser = new Envparser();
    $envparser->load();
    $envparser->run();

    var_dump($envparser->getSystemVars());
```

Will return:

```shell
(array)   ["LANG"]=>
          string(11) "en_US.UTF-8"
          ["DISPLAY"]=>
          string(2) ":1"
          ...
```

Get by variable:

```php
    $envparser = new Envparser();
    $envparser->load();
    $envparser->run();

    echo $envparser->getSystemVars('LANG');
```

Will return:

```shell
"en_US.UTF-8"
```

## Notes

_No notes._

## See also

* [`checkSystemVar()`](checksystemvar.md) - Returns boolean if system variables exists
