# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Development Team Statistics (DTS) - a PHP CLI tool that syncs GitLab data (projects, users, merge requests, commits, events, labels) into a local MySQL database and generates comprehensive developer performance reports with a configurable points-based scoring system.

**Key features:**
- Sync GitLab resources via GitLab API
- Store data locally for fast querying and reporting
- Interactive CLI menu for easy operation
- Clean Architecture with Domain-Driven Design
- Full Docker support

## Common Commands

All commands run inside Docker container. Working directory for PHP commands is `app/`.

### Environment Setup
```bash
# Start Docker environment
docker compose up -d --build

# Install dependencies
docker compose exec php composer install

# Run database migrations
docker compose exec php ./vendor/bin/phinx migrate

# Restart Docker
make dc_restart
```

### Run Application
```bash
make app_run
# or: docker compose exec php php cli.php
```

The interactive menu (`app/cli.php`) provides options to:
- Sync GitLab data (projects, users, merge requests, commits, events, labels)
- Generate developer reports
- View statistics

### Code Quality

```bash
# Run all checks (phpcs + phpstan + rector)
make code_quality

# Individual checks
make phpcs          # Code style (PSR-12 + Slevomat)
make phpcbf         # Auto-fix code style issues
make phpstan        # Static analysis (level 5)
make rector         # Rector dry-run
make rectorbf       # Apply Rector fixes
```

### Testing

```bash
# Run all tests
docker compose exec php ./vendor/bin/phpunit

# Run specific test
docker compose exec php ./vendor/bin/phpunit --filter TestClassName
```

### Database Migrations

```bash
# Create new migration
docker compose exec php ./vendor/bin/phinx create MigrationName

# Run migrations
docker compose exec php ./vendor/bin/phinx migrate

# Rollback last migration
docker compose exec php ./vendor/bin/phinx rollback
```

### Git Operations

```bash
# Clean merged branches
make git_clear

# Pull all projects in ./projects/ directory
make git_projects_pull
```

## Architecture

Clean Architecture with DDD. All source code in `app/src/`:

```
Application/           # Use cases - orchestrate domain logic
├── Cli/               # ExitUseCase, MenuUseCase
├── Gitlab/            # Sync* use cases for each GitLab entity:
│   ├── SyncGitlabDataUseCase
│   ├── SyncGitlabProjectsUseCase
│   ├── SyncGitlabUsersUseCase
│   ├── SyncGitlabUserEventsUseCase
│   ├── SyncGitlabProjectEventsUseCase
│   ├── SyncGitlabProjectMergeRequestsUseCase
│   ├── SyncGitlabMergeRequestLabelEventsUseCase
│   ├── SyncGitlabProjectCommitsUseCase
│   ├── SyncGitlabProjectCommitStatsUseCase
│   └── SyncGitlabLabelsUseCase
├── Report/            # DevReportUseCase
├── UseCaseInterface.php
└── UseCaseCollection.php

Domain/                # Pure business logic, no framework dependencies
├── Common/
│   └── ValueObject/
├── Gitlab/            # GitLab-specific entities:
│   ├── Commit/
│   ├── CommitStats/
│   ├── Event/
│   ├── Label/
│   ├── MergeRequest/
│   ├── Note/
│   ├── Project/
│   ├── PushData/
│   ├── ResourceLabelEvent/
│   ├── User/
│   └── Common/
└── Git/               # Generic Git entities:
    ├── Commit/
    ├── Project/
    ├── Stats/
    ├── User/
    └── Common/

Infrastructure/        # External integrations & implementations
├── Gitlab/
│   ├── GitlabApiClient
│   ├── GitlabApi*Repository      # API clients (fetch from GitLab)
│   └── GitlabMySql*Repository    # MySQL repositories (local storage)
└── Git/

Presentation/          # Entry points and configuration
├── Cli/
│   └── CLI.php        # Interactive menu loop
└── Config/
    ├── main.php       # Main DI container config
    ├── cli.php        # CLI-specific config
    └── gitlab.php     # GitLab repositories wiring
```

### Key Architecture Patterns

1. **Repository Pattern**: Each entity has two repository implementations:
   - `GitlabApi*Repository` - fetches data from GitLab API
   - `GitlabMySql*Repository` - stores/retrieves data from local MySQL

2. **Dependency Inversion**: Domain interfaces (e.g., `GitlabApiProjectRepositoryInterface`) defined in Domain layer, implemented in Infrastructure layer.

3. **Use Case Pattern**: Each business operation is a separate use case class implementing `UseCaseInterface`.

4. **DI Container**: PHP-DI container configured in `Presentation/Config/` - all dependencies are injected, no static coupling.

5. **Layered Architecture**:
   - Domain = pure business logic, no external dependencies
   - Application = orchestration, use cases
   - Infrastructure = external systems (API, DB)
   - Presentation = entry points, UI, DI config

## Code Standards

**Required:**
- PHP 8.4 with `declare(strict_types=1)` in every file
- PSR-12 coding standard
- Single quotes for strings (double quotes only for interpolation)
- camelCase for variables and methods
- Class constants must have visibility and type hints
- Type hints for all parameters and return types

**Complexity Limits:**
- Cyclomatic complexity: max 5 (absolute 7)
- Cognitive complexity: max 8
- Nesting level: max 2 (absolute 3)

**Tools:**
- PHP_CodeSniffer with PSR-12 + Slevomat Coding Standard
- PHPStan level 5
- Rector for code modernization
- PHPUnit for testing

## Technology Stack

- **PHP 8.4** - Main language
- **MySQL 8.0** - Database
- **GitLab API** - Data source
- **Phinx** - Database migrations (`app/phinx/dts/migrations/`)
- **PHP-DI** - Dependency injection container
- **Guzzle** - HTTP client for API requests
- **Symfony Console** - CLI components
- **PHPUnit** - Testing
- **Docker** - Containerization

## Project Structure

```
dts/
├── app/
│   ├── src/              # Application source code
│   ├── phinx/            # Database migrations
│   ├── cli.php           # CLI entry point
│   └── composer.json     # PHP dependencies
├── docker/               # Docker configuration
├── docker-compose.yml    # Docker services (PHP, MySQL)
├── Makefile              # Convenience commands
├── projects/             # Local Git repositories (for analysis)
├── sql/                  # SQL scripts
└── tests/                # Tests
```

## Common Workflows

### Adding a New GitLab Entity Sync

1. Create Domain entity in `Domain/Gitlab/NewEntity/`
2. Create Domain repository interfaces (API + MySQL)
3. Implement repositories in `Infrastructure/Gitlab/`
4. Create Use Case in `Application/Gitlab/Sync*UseCase.php`
5. Add to `UseCaseCollection.php`
6. Wire dependencies in `Presentation/Config/gitlab.php`
7. Create migration for new tables
8. Add to CLI menu in `Presentation/Cli/CLI.php`

### Database Changes

Always use Phinx migrations - never modify schema directly:
```bash
docker compose exec php ./vendor/bin/phinx create DescriptiveMigrationName
# Edit migration file in app/phinx/dts/migrations/
docker compose exec php ./vendor/bin/phinx migrate
```
