<?php

use CleaniqueCoders\PhpEnvKeyManager\EnvKeyManager;

beforeEach(function () {
    // Create a temporary .env file for testing
    $this->envFilePath = __DIR__.'/temp.env';
    file_put_contents($this->envFilePath, "EXISTING_KEY=existing_value\n");
});

afterEach(function () {
    // Remove the temporary .env file after tests
    if (file_exists($this->envFilePath)) {
        unlink($this->envFilePath);
    }
});

test('it initializes with valid .env file path', function () {
    $envManager = new EnvKeyManager($this->envFilePath);
    expect($envManager)->toBeInstanceOf(EnvKeyManager::class);
});

test('it throws exception for invalid .env file path', function () {
    $invalidPath = __DIR__.'/nonexistent.env';
    expect(fn () => new EnvKeyManager($invalidPath))
        ->toThrow(InvalidArgumentException::class, "Environment file not found at path: {$invalidPath}");
});

test('it sets a new key-value pair in the .env file', function () {
    $envManager = new EnvKeyManager($this->envFilePath);
    $result = $envManager->setKey('NEW_KEY', 'new_value');

    // Assert that the operation was successful
    expect($result)->toBeTrue();

    // Verify that the key-value pair was added to the file
    $envContents = file_get_contents($this->envFilePath);
    expect($envContents)->toContain('NEW_KEY=new_value');
});

test('it updates an existing key in the .env file', function () {
    $envManager = new EnvKeyManager($this->envFilePath);
    $result = $envManager->setKey('EXISTING_KEY', 'updated_value');

    // Assert that the operation was successful
    expect($result)->toBeTrue();

    // Verify that the key was updated in the file
    $envContents = file_get_contents($this->envFilePath);
    expect($envContents)->toContain('EXISTING_KEY=updated_value');
});

test('it returns false if the key update fails (no changes made)', function () {
    // Set up a key with the same value as in the file
    $envManager = new EnvKeyManager($this->envFilePath);
    $result = $envManager->setKey('EXISTING_KEY', 'existing_value');

    // Assert that the operation returned false
    expect($result)->toBeFalse();
});

test('it disables an existing key by commenting it out', function () {
    $envManager = new EnvKeyManager($this->envFilePath);
    $result = $envManager->disableKey('EXISTING_KEY');

    // Assert that the operation was successful
    expect($result)->toBeTrue();

    // Verify that the key was commented out
    $envContents = file_get_contents($this->envFilePath);
    expect($envContents)->toContain('#EXISTING_KEY=existing_value');
});

test('it returns false if the key is already disabled', function () {
    $envManager = new EnvKeyManager($this->envFilePath);

    // First, disable the key
    $envManager->disableKey('EXISTING_KEY');
    // Attempt to disable it again
    $result = $envManager->disableKey('EXISTING_KEY');

    // Assert that the operation returned false
    expect($result)->toBeFalse();
});

test('it enables a previously disabled key', function () {
    $envManager = new EnvKeyManager($this->envFilePath);

    // First, disable the key
    $envManager->disableKey('EXISTING_KEY');
    // Then, enable the key
    $result = $envManager->enableKey('EXISTING_KEY');

    // Assert that the operation was successful
    expect($result)->toBeTrue();

    // Verify that the key was uncommented
    $envContents = file_get_contents($this->envFilePath);
    expect($envContents)->toContain('EXISTING_KEY=existing_value');
    expect($envContents)->not->toContain('#EXISTING_KEY=existing_value');
});

test('it returns false if the key is already enabled', function () {
    $envManager = new EnvKeyManager($this->envFilePath);

    // Ensure the key is enabled
    $result = $envManager->enableKey('EXISTING_KEY');

    // Assert that the operation returned false as it was already enabled
    expect($result)->toBeFalse();
});
