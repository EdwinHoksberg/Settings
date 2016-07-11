# Settings
A very simple library for managing application settings with the so-called dot notation. Mostly used by my personal projects.

## Installation
#### With composer
Execute this command in your terminal:
```
composer require edwinhoksberg/settings
```
Or add this line to your composer.json `require` section:
```
"edwinhoksberg/settings": "~1.0"
```
And run `composer update`

#### Without composer
Just require the `Settings.php` file in your project:
```
require 'src/Settings.php';
```

## How to use
```
// settings.php should just be a return statement with an array.
// See tests/fixtures/settings.php for an example.
Settings::loadFromFile('settings.php');

// Load from an array
Settings::loadFromArray([ 'settings' => ... ]);

// Get a setting value using dot notation
Settings::get('database.username', 'defaultuser');

// Retrieve the entire settings array
Settings::getAll();

// You can also set/replace settings with dot notation
Settings::set('database.password', 'toor');

// Check if a setting value exists
Settings::has('database.password'); // Returns true or false

// Remove all settings
Settings::clear();
```

## Tests
The test files are located in the `tests` directory, and can be run with phpunit: 
```
phpunit -c phpunit.dist.xml
```
