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

2. Set up the environment (build containers, install deps, run migrations):
```bash
make dc_setup
```

3. Configure GitLab API access (update configuration as needed)

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

## Scoring System

DTS uses a configurable points-based scoring system to evaluate developer contributions. The final score for each developer is a **weighted sum** of all metrics:

```
Score = (MRs Created × W1) + (Approvals Given × W2) + (MRs Merged × W3) +
        (MRs Merged with Approval × W4) + (MRs Tested × W5) +
        (Lines Added × W6) + (Lines Deleted × W7) +
        (Self-Approvals × W8) + (Direct Commits × W9)
```

All weights are configured via environment variables in `.env`. Reports only include **active users** and only count contributions **after the report start date** (defaults to 2 weeks ago).

### How Each Metric is Calculated

#### Merge Request Activity

Metrics that track the creation and review process:

| Variable | Default | Description |
|---|---|---|
| `POINTS_MERGE_REQUEST_CREATED` | `1.0` | Points per created merge request |
| `POINTS_APPROVALS_GIVEN` | `2.0` | Points for approving/reviewing others' merge requests |

- **MRs Created** — counts MRs created after the report start date, excluding MRs where the source branch is the project's default branch.
- **Approvals Given** — counts all `approved` events authored by the user across all MRs.

#### Merged Merge Requests

A merged MR is the tangible result of a developer's work that brings value to the project. Only MRs merged **to the project's default branch** are counted:

| Variable | Default | Description |
|---|---|---|
| `POINTS_MERGE_REQUEST_MERGED` | `5.0` | Points per successfully merged MR |
| `POINTS_MERGE_REQUEST_APPROVED` | `3.0` | Points for merged MR that received approval from other developers |
| `POINTS_MERGE_REQUEST_TESTED` | `2.0` | Points for merged MR marked as tested (via label from `REPORT_TESTED_LABELS`) |

- **MRs Merged** — counts MRs with state `merged` where `merged_at` is after the report start date and the target branch is the default branch.
- **MRs Merged with Approval** — same as above, but additionally requires at least one active approval (an `approved` event with no later `unapproved` event from the same reviewer).
- **MRs Tested** — counts distinct merged MRs where a label from `REPORT_TESTED_LABELS` (e.g. `Test Ok`) was added and not subsequently removed. Only calculated if `REPORT_TESTED_LABELS` is configured.

#### Lines of Code

| Variable | Default | Description |
|---|---|---|
| `POINTS_LINES_ADDED` | `0.001` | Points per added line of code |
| `POINTS_LINES_REMOVED` | `0.002` | Points per removed line of code |

- Lines are summed from commit stats (`additions` / `deletions`) for commits created after the report start date.
- Git commits are matched to GitLab users via email through the `gitlab_user_x_git_user` bridge table.

#### Informational Metrics

These metrics are displayed in reports for transparency but do not affect the score (default weight `0.0`):

| Variable | Default | Description |
|---|---|---|
| `POINTS_SELF_APPROVALS` | `0.0` | Self-approvals — tracked but not scored |
| `POINTS_DIRECT_COMMITS_TO_MAIN` | `0.0` | Direct commits to main/master branch — tracked but not scored |

- **Self-Approvals** — counts `approved` events where the author of the MR is the same user who approved it.
- **Direct Commits to Main** — counts `pushed to` events to the default branch, excluding merge commits (commits with titles starting with `Merge branch`).

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
make phinx_create name=MigrationName
```

Run migrations:
```bash
make phinx_migrate
```

Rollback last migration:
```bash
make phinx_rollback
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
