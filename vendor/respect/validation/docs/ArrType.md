# ArrType

- `v::arrType()`

Validates if the input is an array or traversable object.

```php
v::arrType()->validate(array()); //true
v::arrType()->validate(new ArrayObject); //true
```

See also:

  * [Each](Each.md)
  * [Key](Key.md)
