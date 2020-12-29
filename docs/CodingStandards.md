# Coding Standards

The aim is to follow most of [PSR-12 "Extended Coding Style"](https://www.php-fig.org/psr/psr-12/), as well as [Symfony Coding Standards](https://symfony.com/doc/current/contributing/code/standards.html#symfony-coding-standards-in-detail).

> TODO: Work in Progress: PHP-CS Will be used with the developer's IDE as a code linter/fixer


## Some other practices should be observed


### Equals / Identical comparison

Whenever possible, we SHOULD refrain from using the “equal `==`” operator and use the “Identical `===`” operator instead.


* Equal: `$a == $b` is `true` if `$a` is equal to `$b`, after type juggling. bad practice
* Identical: `$a === $b` is `true` if `$a` is equal to `$b`, and they are of the same type.

[Read more about PHP comparison operators](https://www.php.net/manual/en/language.operators.comparison.php).


### Variable and object instance name

We MUST always refrain from prefixing a variable name with its type.

bad:
```php
$str_c = ‘red’;
$o_myobject = new Car(‘toyota’);
$int_iTab = 0;
```

good:
```php
$color = ‘red’;
$toyotaCar = new Car(‘toyota’);
$tabIndex = 0;
```

When a variable represents an object's Class, it is RECOMMENDED to have PascalCase used. The first letter SHOULD be in uppercase to indicate a complex structure and not a simple scalar value. i.e: `FlyingMachine`

To have a seamless experience reading the code, it is REQUIRED to ease the reading by using variable and constant names that are intuitive and speaking for themselves.

Acronyms should retain their capital letters when placed at the end of a variable’s name:
```php
$lastUpdatedDateUTC = ...
$isAgencyCIA = false;
```

Boolean constant and variables MUST start with ‘is’, ‘has’, ‘had’, ‘was’, ‘were’:
```php
$isBlue = true;
$wasUpdatedLastWeek = true;
$hasAlreadyPaidOnce = true;
$wereBulkImported = false;
```

### Date, DateTimes and TimeZones

NEVER rely on the default value for the default timezone as it can be changed in php files, php config files, and always fallback to the server’s OS config. Depending on where your code is deployed (server locale), and your level of config optimisation, relying on a default timezone will result in inconsistencies with your saved dates in database and server side logic.

All dates MUST be stored and manipulated as from the UTC timezone.
UTC dates SHOULD only be converted to a local date format upon being displayed for a specific user.

```php
$nowUTC = new \DateTime('now', new \DateTimeZone('UTC'));
$nowFR = clone($nowUTC);
$nowFR->setTimezone(new \DateTimeZone('Europe/Paris'));
echo $nowFR->format(\DateTimeInterface::ISO8601);
```

Dates instantiated from a static source like a database fetch, SHOULD be instances of \DateTimeImmutable


### End of file

Each code file must end with an empty new line and not include a closing php tag `?>`

----
* Back to [README](../README.md)
