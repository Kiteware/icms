# NumericVal

- `v::numericVal()`

Validates on any numeric value.

```php
v::numericVal()->validate(-12); //true
v::numericVal()->validate('135.0'); //true
```

See also:

  * [IntValue](IntValue.md)
  * [Digit](Digit.md)
