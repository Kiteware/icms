# Type

- `v::type(string $type)`

Validates the type of input.

```php
v::type('bool')->validate(true); //true
v::type('callable')->validate(function (){}); //true
v::type('object')->validate(new stdClass()); //true
```

See also

  * [ArrType](ArrType.md)
  * [BoolType](BoolType.md)
  * [FloatType](FloatType.md)
  * [Instance](Instance.md)
  * [IntVal](IntVal.md)
  * [ObjType](ObjType.md)
  * [StrType](StrType.md)
