# checkSystemVar

Returns boolean of existed system variable

## Description

```php
checkSystemVar(string $key)
```

Check if exists system variable.

## Parameters

__key__
: Find by a key of system variable

## Return values

__boolean__
: Returns a TRUE if exists system value otherwise FALSE

## Examples

Get all environment system variables:

```php
    $envparser = new Envparser();
    $envparser->load();
    $envparser->run();

    var_dump($envparser->checkSystemVar('LANG'));
```

Will return:

```shell
(bool) true
```

## Notes

_No notes._

## See also

* [`getSystemVars()`](getsystemvars.md) - Fetch all or one system variables