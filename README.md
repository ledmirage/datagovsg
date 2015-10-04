# datagovsg

This is a laravel 5 package to provide a simple interface to data.gov.sg API.

Read more here:
- http://www.data.gov.sg/
- http://www.data.gov.sg/common/developer.aspx

Currently supported dataset
- NEA

## Install

Via Composer

``` bash
$ composer require ledmirage/datagovsg
```

## Usage

``` php
    $nea_obj = new Ledmirage\Datagovsg\Nea();
    dd(json_decode($nea_obj->psiFetchJson ()));
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email xieer86@gmail.com instead of using the issue tracker.

## Credits

- [xieer][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

