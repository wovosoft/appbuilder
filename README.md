# Laravel Application Builder

[![Latest Version on Packagist](https://img.shields.io/packagist/v/wovosoft/appbuilder.svg?style=flat-square)](https://packagist.org/packages/wovosoft/appbuilder)
[![Total Downloads](https://img.shields.io/packagist/dt/wovosoft/appbuilder.svg?style=flat-square)](https://packagist.org/packages/wovosoft/appbuilder)
![GitHub Actions](https://github.com/wovosoft/appbuilder/actions/workflows/main.yml/badge.svg)

This laravel package is used to create basic scaffolding of a Laravel Application. 
It installs required dependencies and configures everything out of the box.

## Installation
The package uses default authentication provided by https://github.com/laravel/breeze
So, first you need to follow the instructions of `Laravel Breeze`. Then, follow the next steps.

You just need this package during development, so
it can be installed via composer with `--dev` flag:

```shell
composer require wovosoft/appbuilder --dev
```

## Usage
Run the following command. This will copy and configure all the required dependencies and configurations.
```bash
php artisan builder:install
```

If Everything runs smoothly, configure database in `.env` file. Then run the following commands:
```shell
php artisan storage:link
php artisan migrate --seed
```
The last command will seed default users and roles. 

Browse the application. Use following user credentials to login:
```shell
Email: admin@gmail.com
Password: admin12346789
```

## Dependencies

- Vuejs
- Bootstrap Vue

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email wovosoft@gmail.com instead of using the issue tracker.

## Credits

-   [Narayan Adhikary](https://github.com/wovosoft)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
