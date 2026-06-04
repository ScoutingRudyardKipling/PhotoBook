### Project Development Guide

This document provides essential information for developers working on the PhotoBook project.

### Build & Configuration

The project uses Docker for local development, managed via a `Makefile`.

#### Prerequisites
- Docker and Docker Compose (v2.x)
- `make` utility

#### Initial Setup
To set up the project for the first time:
```bash
make setup
```
This command builds the images, creates the `.env` file, starts the containers, generates the application key, runs migrations, and seeds the database.

#### Common Commands
- **Start project**: `make up`
- **Stop project**: `make down`
- **Access PHP container shell**: `make sh`
- **Build images**: `make build-images`
- **Run phpunit**: `make phpunit`
- **Run linter**: `make lint`

### Testing

The project uses PHPUnit for testing. Configuration is located in `phpunit.xml`.

#### Running Tests
To run tests, you must execute them within the PHP container.

**Running all tests:**
```bash
make sh
./vendor/bin/phpunit
```
Or from the host:
```bash
make phpunit
```

#### Adding New Tests
- **Unit Tests**: Place in `tests/Unit/`. Class names should end with `Test.php`.
- **Feature Tests**: Place in `tests/Feature/`. Class names should end with `Test.php`.

Example of a simple Unit Test:
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;

class MyNewTest extends TestCase
{
    public function test_something_works()
    {
        $this->assertTrue(true);
    }
}
```

### Code Quality and Style

The project enforces strict code style and quality rules using several tools defined in `composer.json`.

#### Linting
You can run all linting tools (PHPCS, PHPMD, PHPStan) using:
```bash
composer lint
```
Inside the PHP container, or from the host:
```bash
make lint
```

- **PHP CodeSniffer (PHPCS)**: Uses `PSR12` and `PSR2` along with customized PEAR and Squiz rules. Configuration: `phpcs.xml`.
- **PHP Mess Detector (PHPMD)**: Configuration: `phpmd.xml`.
- **PHPStan**: Static analysis tool. Configuration: `phpstan.neon`. Level 3 is currently enforced.

#### Code Formatting
To automatically fix many style violations:
```bash
composer lint-fix
```
(Inside the PHP container).

### Additional Information
- **Environment**: The `scripts/traefik.sh` script is used to manage a Traefik proxy for the project.
- **Docker Compose**: The project uses multiple compose files: `docker-compose.yml` and `docker-compose-helpers.yml`. Always include both and the `.env` file when running commands manually from the host.
