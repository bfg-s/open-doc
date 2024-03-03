# Open Doc

[![GitHub Workflow Status](https://github.com/bfg-s/open-doc/workflows/Run%20tests/badge.svg)](https://github.com/bfg-s/open-doc/actions)
[![styleci](https://styleci.io/repos/CHANGEME/shield)](https://styleci.io/repos/CHANGEME)

[![Packagist](https://img.shields.io/packagist/v/bfg/open-doc.svg)](https://packagist.org/packages/bfg/open-doc)
[![Packagist](https://poser.pugx.org/bfg/open-doc/d/total.svg)](https://packagist.org/packages/bfg/open-doc)
[![Packagist](https://img.shields.io/packagist/l/bfg/open-doc.svg)](https://packagist.org/packages/bfg/open-doc)

Package description: The package for creating documentation from laravel code.
Based on the you can create a documentation for your project.

## Installation

Install via composer
```bash
composer require bfg/open-doc
```

### Publish package assets

```bash
php artisan vendor:publish --provider="Bfg\OpenDoc\ServiceProvider"
```

## Usage

For generating documentation you can use the command:
```cli
php artisan doc:generate
```

## Security

If you discover any security related issues, please email
instead of using the issue tracker.

## Credits

- [](https://github.com/bfg-s/open-doc)
- [All contributors](https://github.com/bfg-s/open-doc/graphs/contributors)
