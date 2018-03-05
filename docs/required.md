# required

Instance of variable validator and variable validators

## Description

```php
required($envvariable)
```

Returns instance of variable validator.
Throws on VariableException if variable not exists.

## Parameters

__envvariable__
: A variable name

## Return values

__instance__
: Return instance if variable exists

__Exception__
1. VariableExceptions throws if a variable not exists

## Examples

Example #1 Exception
```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser('.env');
$envparser->load();
$envparser->run();

try {
    $envparser->required('FOOVAR');
} catch ($e) {
    // Throws Exception
}
```

## VALIDATION TYPES

A value for specific variable can be checking on types:
* empty value
* boolean type
* number type
* float type

> A validation native uses ValueParser that is used to getValue() for parsing values.

### Empty validation

Returns true if empty value or false if value is not an empty.

```php
isEmpty()
```

Example #1 Empty check

```shell
FOOVAR="value"
FOOVAR2=""
```

```php
$envparser->required('FOOVAR')->isEmpty(); # returns FALSE
$envparser->required('FOOVAR2')->isEmpty(); # returns TRUE
```

### Boolean validation

Returns true if type of value is boolean.

```php
isBoolean()
```

Example #1 Boolean check

```shell
VARBOOL1=yes
VARBOOL2=true
VARBOOL3=false
VARBOOL4=falsew
```

```php
$envparser->required('VARBOOL1')->isBoolean(); # returns TRUE
$envparser->required('VARBOOL2')->isBoolean(); # returns TRUE
$envparser->required('VARBOOL3')->isBoolean(); # returns TRUE
$envparser->required('VARBOOL4')->isBoolean(); # returns FALSE
```

For values like `y/n` or `1/0` you can set to `setStrict(true)` value:

```php
$envparser->required('VARBOOL1')->setStrict(true)->isBoolean(); # returns TRUE
```

### Number validation

Returns true if type of value is numeric, number value without float.

```php
isNumber()
```

Example #1 Number check

```shell
NUMBER=1234567890
```

```php
$envparser->required('NUMBER')->isNumber(); # returns TRUE
```

### Float number validation

Returns true if type of value is decimal, floating type.

```php
isFloat()
```

Example #1 Float number check

```shell
NUMBER=1234.567890
NUMBER2=1234567890
```

```php
$envparser->required('NUMBER')->isNumber(); # returns TRUE
$envparser->required('NUMBER2')->isNumber(); # returns FALSE
```

## VALIDATION VALUES

A variable can check values that must match or throws variables on array values.

```php
checkValues($value)
checkValues(array($value))
```

```shell
NUMBER=1234.567890
NUMBER2=1234567890
```

Example:

```php
$envparser->required('NUMBER')->checkValues('1234.567890'); # returns TRUE
$envparser->required('NUMBER')->checkValues(array('56'); # throws exception
```

Exception throws on `ValidationException` class.

## Notes

> To call `isEmpty()`, `isNumber()` and other validation function use reference.

## See also

_No documents._
