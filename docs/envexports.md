# Exports and setenv variables

A variable can be prefix set with export or setenv.
Export is using on ksh, bash or bourne shells.
Setenv is using on CSH shells.
SetEnv for Apache camel case definition supports parsing.

## Examples

Export sample:

```shell
export FOO1=foo1 #comment
```

Will return:

```shell
(string) 'foo1'
```

Setenv CSH sample:

```shell
setenv FOO1=foo1 #comment
```

Apache camel case sample:

```shell
SetEnv FOO1=foo1 #comment
```

## Notes

_No notes._

## See also

_No documents._
