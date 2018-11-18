# DiscordLogger

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![StyleCI][ico-styleci]][link-styleci]

Package to auto-send the logs to a Discord server.

## Installation

Via Composer

``` bash
$ composer require restray/discordlogger
```


Publish the configuration :

```bash
php artisan vendor:publish --provider=Restray\\DiscordLogger\\Providers\\MonologDiscordServiceProvider
```


Add the driver to logging file :

```php
'channels' => [
    ...,
    'discord' => [
        'driver' => 'monolog',
        'handler' => Restray\DiscordLogger\Handler\DiscordHandler::class
    ],

    'discord-single' => [
        'driver' => 'stack',
        'channels' => ['single', 'discord'],
    ],
    ...,
],
```

## Usage

Use the Driver on all of your project :


In your .env :

```env
LOG_CHANNEL=discord-single
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

If you discover any security related issues, please email contact@restray.org instead of using the issue tracker.

## Credits

- [Restray][link-author]
- [All Contributors][link-contributors]

## License

Apache2.0. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/restray/discordlogger.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/restray/discordlogger.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/158100759/shield

[link-packagist]: https://packagist.org/packages/restray/discordlogger
[link-downloads]: https://packagist.org/packages/restray/discordlogger
[link-styleci]: https://styleci.io/repos/158100759
[link-author]: https://github.com/restray
[link-contributors]: ../../contributors]