# Laravel Has UUID

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]

Provides support for UUID key type in Eloquent models.

## Installation

Via Composer

``` bash
$ composer require bdelespierre/laravel-has-uuid
```

## Usage

```PHP
namespace App\Models;

use Bdelespierre\HasUuid\HasUuid;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasUuid;
}
```

Migration:

```PHP
Schema::create('users', function (Blueprint $table) {
    $table->uuid('id')->primary();
    // other properties go here...
});
```

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email [benjamin.delespierre@gmail.com](mailto:benjamin.delespierre@gmail.com) instead of using the issue tracker.

## Credits

- [Benjamin Delespierre][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bdelespierre/laravel-has-uuid.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bdelespierre/laravel-has-uuid.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bdelespierre/laravel-has-uuid/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/145884475/shield
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/bdelespierre/laravel-has-uuid.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/bdelespierre/laravel-has-uuid
[link-downloads]: https://packagist.org/packages/bdelespierre/laravel-has-uuid
[link-travis]: https://travis-ci.org/bdelespierre/laravel-has-uuid
[link-styleci]: https://styleci.io/repos/145884475
[link-scrutinizer]: https://scrutinizer-ci.com/g/bdelespierre/laravel-has-uuid
[link-author]: https://github.com/bdelespierre
[link-contributors]: ../../contributors]
