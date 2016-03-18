# HexColor

- `v::hexColor()`

Validates a hex RGB color

```php
v::hexColor()->validate('#FFFAAA'); //true
v::hexColor()->validate('123123'); //true
v::hexColor()->validate('FCD'); //true
```

See also:

  * [Vxdigit](Vxdigit.md)
