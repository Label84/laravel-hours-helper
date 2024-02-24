# Laravel Hours Helper

[![Latest Stable Version](https://poser.pugx.org/label84/laravel-hours-helper/v/stable?style=flat-square)](https://packagist.org/packages/label84/laravel-hours-helper)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)
[![Total Downloads](https://img.shields.io/packagist/dt/label84/laravel-hours-helper.svg?style=flat-square)](https://packagist.org/packages/label84/laravel-hours-helper)
![GitHub Workflow Status](https://img.shields.io/github/actions/workflow/status/label84/laravel-hours-helper/run-tests.yml?branch=master&style=flat-square)

With ``laravel-hours-helper`` you can create an ``Illuminate\Support\Collection`` of dates and/or times with a specific interval for a specific period. This helper could be useful in generating dropdown selections for a calendar meeting invite or scheduling the duration of an event. This helper also allows you to define the date formatting for each interval and to exclude intervals within the specific period.

- [Laravel Support](#laravel-support)
- [Installation](#installation)
- [Usage](#usage)
- [Tests](#tests)
- [License](#license)

## Laravel Support

| Version | Release |
|---------|---------|
| 11.x    | ^1.3    |
| 10.x    | ^1.3    |
| 9.x     | ^1.3    |

## Installation

Install the package via composer:

```sh
composer require label84/laravel-hours-helper
```

## Usage

```php
use Label84\HoursHelper\Facades\HoursHelper;

$hours = HoursHelper::create('08:00', '09:30', 30);

// Illuminate\Support\Collection
0 => '08:00',
1 => '08:30',
2 => '09:00',
3 => '09:30',
```

### Example 1: time format

```php
use Label84\HoursHelper\Facades\HoursHelper;

$hours = HoursHelper::create('11:00', '13:00', 60, 'g:i A');

// Illuminate\Support\Collection
0 => '11:00 AM',
1 => '12:00 PM',
2 => '1:00 PM',
```

### Example 2: exclude times

```php
use Label84\HoursHelper\Facades\HoursHelper;

$hours = HoursHelper::create('08:00', '11:00', 60, 'H:i', [
    ['09:00', '09:59'],
    // more..
]);

// Illuminate\Support\Collection
0 => '08:00',
1 => '10:00',
2 => '11:00',
```

### Example 3: past midnight

```php
use Label84\HoursHelper\Facades\HoursHelper;

$hours = HoursHelper::create('23:00', '01:00', 60);

// Illuminate\Support\Collection
0 => '23:00',
1 => '00:00',
2 => '01:00',
```

### Example 4: multiple days

```php
use Label84\HoursHelper\Facades\HoursHelper;

$hours = HoursHelper::create('2022-01-01 08:00', '2022-01-01 08:30', 15, 'Y-m-d H:i');

// Illuminate\Support\Collection
0 => '2022-01-01 08:00',
1 => '2022-01-01 08:15',
2 => '2022-01-01 08:30',
```

You can find more examples in the test directory: [tests/HoursHelperTest.php](tests/HoursHelperTest.php)

## Tests

```sh
./vendor/bin/phpstan analyze
./vendor/bin/phpunit
```

## License

[MIT](https://opensource.org/licenses/MIT)
