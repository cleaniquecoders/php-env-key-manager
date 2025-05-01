# Changelog

All notable changes to `php-env-key-manager` will be documented in this file.

## Added Laravel 12 and PHP 8.4 Support - 2025-05-01

### What's Changed

* Bump dependabot/fetch-metadata from 2.2.0 to 2.3.0 by @dependabot in https://github.com/cleaniquecoders/php-env-key-manager/pull/4
* Bump aglipanci/laravel-pint-action from 2.4 to 2.5 by @dependabot in https://github.com/cleaniquecoders/php-env-key-manager/pull/5

### New Contributors

* @dependabot made their first contribution in https://github.com/cleaniquecoders/php-env-key-manager/pull/4

**Full Changelog**: https://github.com/cleaniquecoders/php-env-key-manager/compare/v1.0.0...1.1.0

## v1.0.0 - 2024-11-11

### Release v1.0.0 - `cleaniquecoders/php-env-key-manager`

**Full Changelog**: https://github.com/cleaniquecoders/php-env-key-manager/commits/v1.0.0

We are excited to announce the initial release of `cleaniquecoders/php-env-key-manager`! This package provides a framework-agnostic solution for managing environment variables directly in `.env` files. Designed for flexibility and simplicity, it allows developers to easily set, disable, and enable environment keys across any PHP application, with specific integrations for Laravel, Symfony, and CodeIgniter.

##### Key Features

- **Set Key-Value Pairs**: Add or update environment keys in the `.env` file using `setKey`.
- **Enable Keys**: Activate an environment key by uncommenting it in the `.env` file.
- **Disable Keys**: Deactivate an environment key by commenting it out in the `.env` file.
- **Framework Agnostic**: Usable in any PHP project with minimal configuration.
- **Framework-Specific Integrations**:
  - **Laravel**: Register as a singleton in `AppServiceProvider` for easy use across the application.
  - **Symfony**: Utilize `EnvKeyManager` in Symfony console commands and services.
  - **CodeIgniter**: Easily manage `.env` keys within controllers and custom classes.
  

##### Usage

###### Basic Usage

```php
use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

$envFilePath = __DIR__ . '/.env';
$envManager = new EnvKeyManager($envFilePath);

// Set a key
$envManager->setKey('APP_DEBUG', 'true');

// Disable a key
$envManager->disableKey('APP_DEBUG');

// Enable a key
$envManager->enableKey('APP_DEBUG');


```
##### Installation

Install via Composer:

```bash
composer require cleaniquecoders/php-env-key-manager


```
##### Documentation

For complete usage instructions, refer to the [README](https://github.com/cleaniquecoders/php-env-key-manager#readme).


---

We look forward to your feedback on this initial release!
