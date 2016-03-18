# FloatType

- `v::floatType()`

Validates a floating point number.

```php
v::floatType()->validate(1.5); //true
v::floatType()->validate('1e5'); //true
```
