# Laravel Has UUID

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require bdelespierre/laravel-has-uuid
```

## Usage

Add the following to your models:

```PHP
namespace App;

use Bdelespierre\HasUuid\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Foo extends Model
{
    use HasUuid;
}
```

And this goes in your migrations:

```PHP
Schema::create('foos', function (Blueprint $table) {
    $table->uuid('id');
    // ...
    $table->timestamps();
    $table->primary('id');
});
```

Then you can simply enjoy automatic UUID generation:

```
>>> $f = Foo::create()
=> App\Foo {#2920
     id: "7e50eff6-3973-4f8b-94c4-a9461add6e22",
     updated_at: "2018-08-23 17:07:57",
     created_at: "2018-08-23 17:07:57",
   }
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

If you discover any security related issues, please email benjamin@delespierre.pro instead of using the issue tracker.

## Credits

- [Benjamin Delespierre][link-author]
- [All Contributors][link-contributors]

## License

MIT. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/bdelespierre/has-uuid.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/bdelespierre/has-uuid.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/bdelespierre/has-uuid/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/bdelespierre/has-uuid
[link-downloads]: https://packagist.org/packages/bdelespierre/has-uuid
[link-travis]: https://travis-ci.org/bdelespierre/has-uuid
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/bdelespierre
[link-contributors]: ../../contributors]
