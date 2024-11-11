# PHP Env Key Manager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/cleaniquecoders/php-env-key-manager.svg?style=flat-square)](https://packagist.org/packages/cleaniquecoders/php-env-key-manager) [![Tests](https://img.shields.io/github/actions/workflow/status/cleaniquecoders/php-env-key-manager/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/cleaniquecoders/php-env-key-manager/actions/workflows/run-tests.yml) [![Total Downloads](https://img.shields.io/packagist/dt/cleaniquecoders/php-env-key-manager.svg?style=flat-square)](https://packagist.org/packages/cleaniquecoders/php-env-key-manager)

A framework-agnostic PHP package for easy .env file key management. Seamlessly update, add, or modify environment variables across projects with minimal configuration.

## Installation

You can install the package via composer:

```bash
composer require cleaniquecoders/php-env-key-manager
```

## Usage

### Basic Usage

Following are the basic usage examples for the package:

```php
use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

// Path to your .env file
$envFilePath = __DIR__ . '/.env';
$envManager = new EnvKeyManager($envFilePath);

// Set a key
$envManager->setKey('APP_DEBUG', 'true');

// Disable a key
$envManager->disableKey('APP_DEBUG');

// Enable a key
$envManager->enableKey('APP_DEBUG');
```

---

### Framework-Specific Examples

#### Laravel

To use `EnvKeyManager` in a Laravel application, register it as a singleton in the `AppServiceProvider` to allow easy access across your application.

<details>
<summary>Laravel Usage</summary>

1. **Register as a Singleton**

   In `App\Providers\AppServiceProvider`:

   ```php
   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   public function register()
   {
       $this->app->singleton(EnvKeyManager::class, function ($app) {
           return new EnvKeyManager($app->environmentFilePath());
       });
   }
   ```

2. **Usage in a Command**

   Create a Laravel Artisan command to set, disable, or enable environment keys:

   ```php
   <?php

   namespace App\Console\Commands;

   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;
   use Illuminate\Console\Command;

   class ManageEnvKeyCommand extends Command
   {
       protected $signature = 'env:manage-key {action} {key} {value?}';
       protected $description = 'Manage an environment key';

       protected $envManager;

       public function __construct(EnvKeyManager $envManager)
       {
           parent::__construct();
           $this->envManager = $envManager;
       }

       public function handle()
       {
           $action = $this->argument('action');
           $key = $this->argument('key');
           $value = $this->argument('value');

           switch ($action) {
               case 'set':
                   $this->envManager->setKey($key, $value);
                   $this->info("Key {$key} set to {$value}.");
                   break;

               case 'disable':
                   $this->envManager->disableKey($key);
                   $this->info("Key {$key} has been disabled.");
                   break;

               case 'enable':
                   $this->envManager->enableKey($key);
                   $this->info("Key {$key} has been enabled.");
                   break;

               default:
                   $this->error("Invalid action. Use 'set', 'disable', or 'enable'.");
           }
       }
   }
   ```

</details>

---

#### Symfony

To use `EnvKeyManager` in Symfony, initialize it with the `.env` path, and use it in Symfony commands or services.

<details>
<summary>Symfony Usage</summary>

1. **Initialize `EnvKeyManager`** with Symfony’s `.env` path.

   ```php
   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   $envFilePath = __DIR__ . '/../../.env'; // Adjust the path to your Symfony .env file
   $envManager = new EnvKeyManager($envFilePath);
   ```

2. **Use in a Symfony Command**

   Create a Symfony console command to manage environment keys:

   ```php
   <?php

   namespace App\Command;

   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;
   use Symfony\Component\Console\Command\Command;
   use Symfony\Component\Console\Input\InputArgument;
   use Symfony\Component\Console\Input\InputInterface;
   use Symfony\Component\Console\Output\OutputInterface;

   class ManageEnvKeyCommand extends Command
   {
       protected static $defaultName = 'env:manage-key';

       private $envManager;

       public function __construct(EnvKeyManager $envManager)
       {
           parent::__construct();
           $this->envManager = $envManager;
       }

       protected function configure()
       {
           $this
               ->setDescription('Manage an environment key')
               ->addArgument('action', InputArgument::REQUIRED, 'Action: set, disable, enable')
               ->addArgument('key', InputArgument::REQUIRED, 'The environment key')
               ->addArgument('value', InputArgument::OPTIONAL, 'The value for set action');
       }

       protected function execute(InputInterface $input, OutputInterface $output)
       {
           $action = $input->getArgument('action');
           $key = $input->getArgument('key');
           $value = $input->getArgument('value');

           switch ($action) {
               case 'set':
                   $this->envManager->setKey($key, $value);
                   $output->writeln("Key {$key} set to {$value}.");
                   break;

               case 'disable':
                   $this->envManager->disableKey($key);
                   $output->writeln("Key {$key} has been disabled.");
                   break;

               case 'enable':
                   $this->envManager->enableKey($key);
                   $output->writeln("Key {$key} has been enabled.");
                   break;

               default:
                   $output->writeln("Invalid action. Use 'set', 'disable', or 'enable'.");
                   return Command::FAILURE;
           }

           return Command::SUCCESS;
       }
   }
   ```

</details>

---

#### CodeIgniter

To use `EnvKeyManager` in CodeIgniter, initialize it with the `.env` path and use it within controllers or custom classes.

<details>
<summary>CodeIgniter Usage</summary>

1. **Initialize `EnvKeyManager`** with the CodeIgniter `.env` path.

   ```php
   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   $envFilePath = ROOTPATH . '.env'; // CodeIgniter base path to .env
   $envManager = new EnvKeyManager($envFilePath);
   ```

2. **Use in a CodeIgniter Controller**

   Create a CodeIgniter controller method to manage environment keys:

   ```php
   <?php

   namespace App\Controllers;

   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   class EnvController extends BaseController
   {
       protected $envManager;

       public function __construct()
       {
           $this->envManager = new EnvKeyManager(ROOTPATH . '.env');
       }

       public function manageKey($action, $key, $value = null)
       {
           switch ($action) {
               case 'set':
                   $this->envManager->setKey($key, $value);
                   return "Key {$key} set to {$value}.";

               case 'disable':
                   $this->envManager->disableKey($key);
                   return "Key {$key} has been disabled.";

               case 'enable':
                   $this->envManager->enableKey($key);
                   return "Key {$key} has been enabled.";

               default:
                   return "Invalid action. Use 'set', 'disable', or 'enable'.";
           }
       }
   }
   ```

</details>

---

These framework-specific examples demonstrate how to integrate `EnvKeyManager` seamlessly in Laravel, Symfony, and CodeIgniter, making it easy to manage environment keys within each framework.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Nasrul Hazim Bin Mohamad](https://github.com/nasrulhazim)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
