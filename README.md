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

**Initialize `EnvKeyManager`**: Provide the path to your `.env` file when creating the instance.

   ```php
   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   // Path to your .env file
   $envFilePath = __DIR__ . '/.env';
   $envManager = new EnvKeyManager($envFilePath);
   ```

**Set an Environment Key**: Use `setKey` to add or update a key-value pair.

   ```php
   $key = 'APP_LOGIN_KEY';
   $value = bin2hex(random_bytes(16)); // Example 32-character random key

   if ($envManager->setKey($key, $value)) {
       echo "Successfully set {$key} in .env file.";
   } else {
       echo "Failed to set {$key}. Please ensure the key exists in the .env file.";
   }
   ```

---

### Framework-Specific Examples

#### Laravel

<details>
<summary>Usage in Laravel</summary>

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

   ```php
   <?php

   namespace App\Console\Commands;

   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;
   use Illuminate\Console\Command;
   use Illuminate\Support\Str;

   class GenerateLoginKeyCommand extends Command
   {
       protected $signature = 'generate:login-key {--show : Display the key instead of modifying files}';
       protected $description = 'Generate and set a login key';

       protected $envKeyManager;

       public function __construct(EnvKeyManager $envKeyManager)
       {
           parent::__construct();
           $this->envKeyManager = $envKeyManager;
       }

       public function handle()
       {
           $key = Str::random(32);

           if ($this->option('show')) {
               return $this->line('<comment>'.$key.'</comment>');
           }

           if (!$this->envKeyManager->setKey('APP_LOGIN_KEY', $key)) {
               $this->error('Failed to set APP_LOGIN_KEY in .env');
               return;
           }

           $this->info('Login key set successfully.');
       }
   }
   ```

</details>

#### Symfony

<details>
<summary>Usage in Symfony</summary>

1. **Initialize `EnvKeyManager`** with Symfony’s `.env` path.

   ```php
   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   $envFilePath = __DIR__ . '/../../.env'; // Adjust the path to your Symfony .env file
   $envManager = new EnvKeyManager($envFilePath);
   ```

2. **Use in a Symfony Command**

   Create a Symfony console command and inject `EnvKeyManager`:

   ```php
   <?php

   namespace App\Command;

   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;
   use Symfony\Component\Console\Command\Command;
   use Symfony\Component\Console\Input\InputInterface;
   use Symfony\Component\Console\Output\OutputInterface;

   class GenerateLoginKeyCommand extends Command
   {
       protected static $defaultName = 'app:generate-login-key';
       private $envKeyManager;

       public function __construct(EnvKeyManager $envKeyManager)
       {
           parent::__construct();
           $this->envKeyManager = $envKeyManager;
       }

       protected function execute(InputInterface $input, OutputInterface $output)
       {
           $key = bin2hex(random_bytes(16));

           if ($this->envKeyManager->setKey('APP_LOGIN_KEY', $key)) {
               $output->writeln("Login key set successfully: {$key}");
               return Command::SUCCESS;
           } else {
               $output->writeln("<error>Failed to set APP_LOGIN_KEY in .env</error>");
               return Command::FAILURE;
           }
       }
   }
   ```

</details>

#### CodeIgniter

<details>
<summary>Usage in CodeIgniter</summary>

1. **Set Up**: Define `.env` path and create `EnvKeyManager` instance.

   ```php
   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   $envFilePath = ROOTPATH . '.env'; // CodeIgniter base path to .env
   $envManager = new EnvKeyManager($envFilePath);
   ```

2. **Usage in CodeIgniter Controller**

   In a controller or any other class:

   ```php
   <?php

   namespace App\Controllers;

   use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

   class EnvController extends BaseController
   {
       public function updateEnv()
       {
           $envFilePath = ROOTPATH . '.env';
           $envManager = new EnvKeyManager($envFilePath);
           $key = 'CI_LOGIN_KEY';
           $value = bin2hex(random_bytes(16));

           if ($envManager->setKey($key, $value)) {
               echo "Successfully set {$key} in .env file.";
           } else {
               echo "Failed to set {$key}. Please ensure the key exists in the .env file.";
           }
       }
   }
   ```

</details>

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
