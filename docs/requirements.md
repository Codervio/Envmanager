# Requirements

# Encoding env file

When script parsing it will check in next procedures:

1. _mb_detect_encoding_ will set to _auto_ for automatically detecting encoding from .env file

2. A script will check in encoding list if exists encoding from auto detected _mb_detect_encoding_
If not exists, it will throw a warning message: 

> Encoding "encoding" not found. Check supported encoding using mb_list_encoding() function or use mostly UTF-8 encoding type

3. Convert an encoding type using _mb_convert_encoding_ function.

# Manually set encoding type

```php
setEncoding($encoding = 'UTF-8')
```

## Parameters

__encoding__
: A encoding types defined in mb_list_encodings() values
: Default: UTF-8

## Return values

__void__
: No return

__Exception__
1. Encoding "UTF-12" not found. Check supported encoding using mb_list_encoding() function or use mostly UTF-8 encoding type

## Example

```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser();
$envparser->setEncoding('UTF-7'); # Specified encoding type
$envparser->load();
$envparser->run();

var_dump($envparser->getSystemVars());
```

## To see lists of all encoding types use function:

```php
var_dump(mb_list_encoding);
```

# Get a current active encoding

To get currently detected encoding and print which encoding type is detected use getEncoding() function:

```php
getEncoding()
```

## Return values

__string__
: Encoding type defined in mb_list_encoding() list

## Example

```php
use Codervio\Envmanager\Envparser;

$envparser = new Envparser();
$envparser->load();
$envparser->run();
$envparser->getEncoding(); # Get detected encoding

var_dump($envparser->getSystemVars());
```

## Notes

_No notes._

## See also

* [`checkSuperGlobalsSet()`](checksuperglobalsset.md) - Check if set or get env directive for $_ENV active