# checksuperglobalsset

Check if set or get env directive for $_ENV active.

## Description

```php
checksuperglobalsset()
```

A function checks on php.ini _variables_order_ directive is set to global _G_ or all super globals.
Some system is restricted so you can manually check if is thrown on warning.

## Return values

__boolean__
: Returns a TRUE if directives for setting $_ENV variable passed
 
__exception__
: Returns Runtime exception.

Message example:
> Warning: Set and create globals for $_ENV is disabled. To enable globally, for console run: 'php -d variables_order=EGPCS php.php' or set in php.ini directive: variables_order=EGPCS

## Examples

Get all environment system variables:

```php
    $envparser = new Envparser();
    if ($envparser->checksuperglobalsset()) {
        // Directive passed
    } exception RuntimeException() {
        // Thrown runtime exception
    }
```

## Notes

_No notes._

## See also

* [`requirements`](requirements.md) - Requirements and auto detect encodings
* [`setEncoding()`](requirements.md) - Manually specify encoding
* [`getEncoding()`](requirements.md) - Detect encoding type from file