<?php

namespace CleaniqueCoders\PhpEnvKeyManager;

class EnvKeyManager
{
    protected $envFilePath;

    /**
     * Constructor to set the .env file path.
     */
    public function __construct(string $envFilePath)
    {
        if (! file_exists($envFilePath)) {
            throw new \InvalidArgumentException("Environment file not found at path: {$envFilePath}");
        }

        $this->envFilePath = $envFilePath;
    }

    /**
     * Set or update a key in the environment file.
     */
    public function setKey(string $key, string $value): bool
    {
        return $this->writeNewEnvironmentFileWith($key, $value);
    }

    /**
     * Disable (comment out) a key in the environment file.
     */
    public function disableKey(string $key): bool
    {
        $pattern = $this->keyReplacementPattern($key);
        $input = file_get_contents($this->envFilePath);

        // Check if the key is already disabled (commented)
        if (preg_match("/^#{$key}=/m", $input)) {
            return false; // Key is already disabled
        }

        // Comment out (disable) the key
        $replaced = preg_replace($pattern, '#$0', $input);

        if ($replaced === $input || $replaced === null) {
            return false; // Key not found or already disabled
        }

        file_put_contents($this->envFilePath, $replaced);

        return true;
    }

    /**
     * Enable (uncomment) a key in the environment file.
     */
    public function enableKey(string $key): bool
    {
        $pattern = "/^#({$key}=.*)/m";
        $input = file_get_contents($this->envFilePath);

        // Uncomment (enable) the key
        $replaced = preg_replace($pattern, '$1', $input);

        if ($replaced === $input || $replaced === null) {
            return false; // Key not found or already enabled
        }

        file_put_contents($this->envFilePath, $replaced);

        return true;
    }

    /**
     * Write a new environment file with the given key and value.
     */
    protected function writeNewEnvironmentFileWith(string $key, string $value): bool
    {
        $pattern = $this->keyReplacementPattern($key);
        $input = file_get_contents($this->envFilePath);

        // Attempt to replace the key if it exists
        $replaced = preg_replace(
            $pattern,
            "{$key}={$value}",
            $input
        );

        // If the key does not exist, append it to the file
        if ($replaced === $input || $replaced === null) {
            if (strpos($input, "{$key}={$value}") !== false) {
                // Key and value already exist, so no changes are needed
                return false;
            }

            // Append the new key-value pair
            $replaced = $input.PHP_EOL."{$key}={$value}";
        }

        file_put_contents($this->envFilePath, $replaced);

        return true;
    }

    /**
     * Get a regex pattern that will match the env key with any value.
     */
    protected function keyReplacementPattern(string $key): string
    {
        return "/^{$key}=.*/m";
    }
}
