# Helps

Here is a common mistakes and helps that collected.

## Situation in overriding $_ENV variables

It is common that exists variable in $_ENV, by default it will not override.

You can manually force to override a variable on second parameter:

```php
$envparser->load(true, true);
```

See: * [`load()`](load.md) - Load an environment .env  file or folder with .env files

## Cleanup environment variable

It is possible to manually native unset a variable, insert a code before instance of Envparser():

```php
        unset($_ENV['MYEXISTVAR']);
        putenv("MYEXISTVAR=");
```

It will clean _MYEXISTVAR_ and clean up to empty value or unset.
        