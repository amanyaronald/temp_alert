## Sensy/Scrud

**Sensy/Scrud** is a Laravel package that provides a CRUD (Create, Read, Update, Delete) starter kit. It helps you
quickly scaffold CRUD operations for your models, making it easier to build and maintain your Laravel applications.

### Requirements

- PHP: ^8.0
- Laravel/Framework: ^8.0|^9.0|^10.0|^11.0
- Livewire/Livewire: ^3.0
- Spatie/Laravel-Permission: ^5.0
- Laravel/Jetstream: ^2.0

## Features

- Quick CRUD scaffolding for Eloquent models
- Auto-generates controllers, views, Permissions, and routes
- Customizable templates
- Supports basic and advanced CRUD operations

## Installation

The first step is to follow and make sure you complete installation of jetstream from their official website.

> NOTE: TEAMS is not yet supported so please dont use teams.

To install the package, use Composer:

```bash
composer require sensy/scrud
```

After installing, add the service provider in your providers array in your `app.php` file.

```php
Sensy\Scrud\Providers\ScrudServiceProvider::class,
```

Then run initial installations

```bash****
php artisan s-crud:install
```

## Available Commands

### `s-crud:setup`

Create models without migrations by default. Use the `--m` option to create the with migration.

```bash
php artisan s-crud:setup {ModelName} {--m}
```

### `s-crud:crud`

Scaffold a full CRUD for a given model.

```bash
php artisan s-crud:crud {ModelName}
```

This command will generate:

- A controller for handling CRUD operations
- Views for creating, editing, and listing records
- Routes for accessing the CRUD interface
- Permissions for limiting access
- Menus are registered (Side menu)
- System modules are registered

### `s-crud:create-user`

Create a new user with an optional role.

```bash
php artisan s-crud:create-user {name} {email} {password} {--role=}
```

### `s-crud:extractor`

Extract database data to be synced/Deployed.

```bash
php artisan s-crud:extractor
```

### `s-crud:deploy`

Deploy the system.

```bash
php artisan s-crud:deploy
```

You can use `--live` to deploy to a live environment

```bash
php artisan s-crud:deploy --live
```

## Usage

### Scaffolding CRUD Operations

To scaffold CRUD operations for a model:

```bash
php artisan s-crud:crud ModelName
```

This will generate the necessary controller, views, and routes to manage records for your model.

### Customizing Templates

You can customize the generated templates by editing the files in `resources/views/vendor/scrud/`.

### Configuration

You can configure the package by editing the configuration file located at `config/scrud.php`.

## Example

To scaffold CRUD operations for a `Product` model:

First, set up the model and migration:

```bash
php artisan s-crud:setup Product
```

Optionally, use the `--m` flag to create only the model:

```bash
php artisan s-crud:setup Product --m
```

After modifying the generated files as needed, you can scaffold the CRUD operations:

```bash
php artisan s-crud:crud Product
```

This will generate the necessary controller, views, and routes to manage products in your application.

## Other Assets

### Logo

To modify the logo, replace the file `public/backend/assets/img/logo.png`

## Contributing

Thank you for considering contributing to Sensy/Scrud! Please read the [CONTRIBUTING.md](CONTRIBUTING.md) file for
details on our code of conduct and the process for submitting pull requests.

## License

Sensy/Scrud is open-source software licensed under the [MIT license](LICENSE.md).

## Credits

- [Onen Sensy](https://github.com/onensensy)
- [All Contributors](https://github.com/onensensy/scrud/graphs/contributors)

### TODO

- [x] Paginations on views
- [ ] Enhance Logging
- [x] Add requirement (iseed)
- [ ] Decimal Validation rule enhancement
- [x] Login Page
- [x] Dynamic Way of declaring logo
- [ ] System Configurations(Logo, System Name,etc)
- [ ] Unique costraint fix in the scafold of controllers
- [ ] Place validation vertically for cleaner code
- [ ] Published Views are not working
- [ ] Menu Search
- [ ] Configs (Input driver)
- [ ] File input locally
- [ ] Disable Success/Error messages
- [ ] Add helper into the package
