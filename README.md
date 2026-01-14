# Development Team Statistics (DTS)

A PHP CLI tool that syncs GitLab data and generates developer performance reports with a configurable points-based scoring system.

## Overview

DTS synchronizes data from GitLab (projects, users, merge requests, commits, events) into a local MySQL database and generates comprehensive developer performance reports. The tool uses a points-based scoring system to evaluate developer contributions and activity.

## Features

- Sync GitLab resources (projects, users, merge requests, commits, events, labels)
- Store data locally in MySQL for fast querying and reporting
- Generate developer performance reports with customizable scoring
- Interactive CLI menu for easy operation
- Clean Architecture with Domain-Driven Design
- Full Docker support for easy setup

## Requirements

- Docker
- Docker Compose
- Make (optional, for convenience commands)

## Installation

1. Clone the repository:
```bash
git clone <repository-url>
cd dts
```

2. Start the Docker environment:
```bash
docker compose up -d --build
```

3. Install PHP dependencies:
```bash
docker compose exec php composer install
```

4. Run database migrations:
```bash
docker compose exec php ./vendor/bin/phinx migrate
```

5. Configure GitLab API access (update configuration as needed)

## Usage

### Running the Application

Start the interactive CLI:
```bash
make app_run
```

Or directly:
```bash
docker compose exec php php cli.php
```

The interactive menu will guide you through available operations:
- Sync GitLab data (projects, users, merge requests, commits, events)
- Generate developer reports
- View statistics

### Code Quality Checks

Run all code quality checks:
```bash
make code_quality
```

Individual checks:
```bash
make phpcs          # Check code style (PSR-12)
make phpcbf         # Auto-fix code style issues
make phpstan        # Run static analysis (level 5)
make rector         # Rector dry-run
make rectorbf       # Apply Rector fixes
```

### Testing

Run all tests:
```bash
docker compose exec php ./vendor/bin/phpunit
```

Run specific test:
```bash
docker compose exec php ./vendor/bin/phpunit --filter TestClassName
```

## Architecture

The project follows Clean Architecture principles with Domain-Driven Design:

```
app/src/
├── Application/     # Use cases - orchestrate domain logic
│   ├── Gitlab/      # Sync* use cases for each GitLab entity
│   └── Report/      # DevReportUseCase
├── Domain/          # Pure business logic, no framework dependencies
│   ├── Gitlab/      # Entities per resource type
│   └── Git/
├── Infrastructure/  # External integrations
│   ├── Gitlab/      # API clients + MySQL repositories
│   └── Git/
└── Presentation/    # Entry points and DI
    ├── Cli/         # Interactive CLI menu
    └── Config/      # Dependency injection configuration
```

### Key Principles

- Domain interfaces defined in Domain layer, implemented in Infrastructure
- Separation of concerns with clear boundaries
- Dependency injection for loose coupling
- Repository pattern for data access

## Development

### Code Standards

- PHP 8.4 with strict types (`declare(strict_types=1)`)
- PSR-12 coding standard
- Single quotes for strings
- Cyclomatic complexity: max 5 (absolute 7)
- Cognitive complexity: max 8
- Nesting level: max 2 (absolute 3)
- camelCase for variables
- Class constants must have visibility and type hints

### Database Migrations

Migrations are managed with Phinx and located in `app/phinx/dts/migrations/`.

Create a new migration:
```bash
docker compose exec php ./vendor/bin/phinx create MigrationName
```

Run migrations:
```bash
docker compose exec php ./vendor/bin/phinx migrate
```

Rollback last migration:
```bash
docker compose exec php ./vendor/bin/phinx rollback
```

## Project Structure

- `app/src/` - Application source code
- `app/phinx/` - Database migrations
- `app/cli.php` - CLI entry point
- `docker-compose.yml` - Docker services configuration
- `Makefile` - Convenience commands

## Technology Stack

- PHP 8.4
- MySQL 8.0
- GitLab API integration
- PHPUnit for testing
- PHPStan for static analysis
- PHP_CodeSniffer for code style
- Rector for code modernization
- Phinx for database migrations
- Docker for containerization
