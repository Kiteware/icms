# NotOptional

- `v::notOptional()`

Validates if the given input is not optional or in other words is input mandatory
and required. This function also takes whitespace into account, use `noWhitespace()`
if no spaces or linebreaks and other whitespace anywhere in the input is desired.

```php
v::strType()->notOptional()->validate(''); //false
```

Null values are optional:

```php
v::notOptional()->validate(null); //false
```

Numbers:

```php
v::intVal()->notOptional()->validate(0); //false
```

Optional arrays:

```php
v::arr()->notOptional()->validate(array()); //false
```

Whitespace:

```php
v::strType()->notOptional()->validate('        ');  //false
v::strType()->notOptional()->validate("\t \n \r");  //false
```

See also:

  * [NoWhitespace](NoWhitespace.md)
  * [NullType](NullType.md)
