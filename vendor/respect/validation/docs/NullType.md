# NullType

- `v::nullType()`

Validates if the input is null. This rule does not allow empty strings to avoid ambiguity.

```php
v::nullType()->validate(null); //true
```

See also:

  * [NotEmpty](NotEmpty.md)
