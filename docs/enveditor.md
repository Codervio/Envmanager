# Enveditor

Instance environment for creating environment file.

## Possibilities

- read a file or create a new .env file
- parse full content
- clear a content from current stage
- force to clear all content
- set a new env variable with comment and exports
- add a comment
- add an empty line
- remove variable
- remove comment
- save a new or existing file to .env

## Typical instance

A enveditor can instance as a new file:

```php
$enveditor = new Enveditor('my.env');
// Do with a file
```

or existing instance of Envparser:

```php
$envparser = new Envparser('my.env');
// ...

$enveditor = new Enveditor($envparser);
// Do with a file
```

## Persist environment variable

Environment variable supports to persisting a variable:
- `export` - include/exclude, default: not include
- `key` - name of variable
- `value` - a variable value
- `comment` - a comment information

```php
persist($key, $value, $comment = null, (bool)$export);
```

> You can persist many variables. A same key will be overriden.

Example:

```php
$enveditor = new Enveditor('my.env');
$this->instance->persist('FOO2', 'valuetest', 'mycomment', true);
```

> If a variable exists by key it will overwritten.
> To remove comment use `null` atributte.

## Saving to .env file

After setting a variable to write it can be save to a file:

```php
save();
```

Example:

```php
$enveditor = new Enveditor('my.env');
$this->instance->persist('FOO2', 'valuetest', 'mycomment', true);
$this->save();
```

A file 'my.env' looks like:

```text
export FOO2="valuetest" # mycomment

```

## View or a read content before save

```php
$enveditor = new Enveditor('my.env');
print_r($this->getContent());
```

It will render a content as string that prepared to a file.

> Useful to view a full content of a file.

## Adding a comment

```php
$enveditor = new Enveditor('my.env');
$this->addComment("my comment");
$this->save();
```

Result:

```text
# my comment

```

## Adding an empty line

To add empty line use function:

```php
$enveditor = new Enveditor('my.env');
$this->addEmptyLine();
$this->save();
```

Result:

```text


```

## Remove variable

To remove a variable use remove() function:

```php
remove($key)
```

This will remove a value from a key.

## Remove comment

To remove a comment use:

```php
$enveditor = new Enveditor('my.env');
$this->addComment('comment1');
$this->addComment('comment2');
$this->removeComment('comment1');
$this->save();
```

Result:

```text
comment2

```

## Remove a file

```php
removeFile($key)
```

Example:

```php
$enveditor = new Enveditor('my.env');
$this->removeFile();
```

> Will remove my.env file. It can use after save() function.

## Clear previous content

After set a variable clearContent() all previous variables will be removed.

```php
clearContent()
```

Example:

```php
$enveditor = new Enveditor('my.env');
$this->addComment("comment 1");
$this->addComment("comment 2");
$this->clearContent();
$this->addComment("comment 3");
$this->save();
```

A result will remove all previous to clearContent() values:

```text
# comment 3

```

## Force to clean a file

To remove all contents in a file even if is after defined new variable use:

```php
forceClear()
```

Example:

```php
$enveditor = new Enveditor('my.env');
$this->forceClear();
$this->addComment("comment 1");
$this->addComment("comment 2");
$this->save();
```

A file will empty.

## Notes

> A new line will automatically added at end of file.

## See also

_No documents._