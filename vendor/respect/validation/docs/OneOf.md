# OneOf

- `v::oneOf(v $v1, v $v2, v $v3...)`

This is a group validator that acts as an OR operator.

```php
v::oneOf(
    v::intVal(),
    v::float()
)->validate(15.5); //true
```

In the sample above, `v::intVal()` doesn't validates, but
`v::float()` validates, so oneOf returns true.

`v::oneOf` returns true if at least one inner validator
passes.

Using a shortcut

```php
v::intVal()->addOr(v::float())->validate(15.5); //true
```

See also:

  * [AllOf](AllOf.md)
  * [NoneOf](NoneOf.md)
  * [When](When.md)
