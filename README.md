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

in config/app.php, add this

``` php
    Ledmirage\Datagovsg\DatagovsgServiceProvider::class,
```

## Publish config

Run:

``` bash
$ php artisan vendor:publish
```

## Configuration

After publish, you have this config/datagovsg.php file, open and update the API key, eg:

``` php
    'nea-key' => 'AABBCCDDEEFFGGHHIIJJKKLLMMNNOOPPQQ',
```

You need to go to different gov.sg site to get different key.


## Usage

``` php
    $nea_obj = new Ledmirage\Datagovsg\Nea();
    dd(json_decode($nea_obj->psiFetchJson ()));
```


## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.


## Credits

- xieer86@gmail.com


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

