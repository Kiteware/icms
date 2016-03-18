# TrueVal

- `v::trueVal()`

Validates if a value is considered as `true`.

```php
v::trueVal()->validate(true); //true
v::trueVal()->validate(1); //true
v::trueVal()->validate('1'); //true
v::trueVal()->validate('true'); //true
v::trueVal()->validate('on'); //true
v::trueVal()->validate('yes'); //true
```

See also

  * [FalseValue](FalseValue.md)
