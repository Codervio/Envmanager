# setStrictBool

Parse value to boolen type on non-standard values such as 'y/n' or '1/0'.
Sample/Default: By default it will parse 'y' to true boolean type.

## Description

```php
setStrictBool((bool) true)
```

It will parse to boolean type from string type:

STRICT: true|yes|on|1|y or false|no|off|0|n/
NO STRICT: true|yes|on or false|no|off

## Return values

__state__
: Set TRUE - parse y/n or 1/0 to boolean type
: Set FALSE - parse native
: Default: TRUE

## Examples

Set active strict type TRUE:

Parse y character to true:

```shell
STRICTVARY=y
```

A code:

```php
$envfile = $this->getFileTest('.env');

$envparser = new Envparser($envfile, true);
$envparser->setStrictBool(true);
$envparser->load();

$envparser->run();

if ($envparser->getValue('STRICTVARY')) {
    // Triggered true
}
```

A result will return true instead sign 'y'.

## Notes

> getenv() function setStrictBool will not work due it will 'y' convert to '1' as true result.
> It will works proprely on $_ENV superglobal variables due to keep type of value.

## See also

_No info._