# Development Team Statistics (DTS)

A PHP CLI tool that syncs GitLab data and generates developer performance reports with a configurable points-based scoring system.

## Overview

DTS synchronizes data from GitLab (projects, users, merge requests, commits, events, labels) into a local MySQL database and generates comprehensive developer performance reports. The tool uses a points-based scoring system to evaluate developer contributions and activity.

## Features

- Sync GitLab resources (projects, users, merge requests, commits, events, labels)
- Store data locally in MySQL for fast querying and reporting
- Generate developer performance reports with customizable scoring
- Dual report output: Markdown table (CLI) and interactive HTML with sortable columns
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

3. Copy `.env.example` to `.env` and configure:
```env
# GitLab
GITLAB_URL=https://gitlab.example.com
GITLAB_TOKEN=your-api-token
GITLAB_GROUP_ID=123
GITLAB_SYNC_DATE_AFTER=2024-01-01

# MySQL
MYSQL_URL=mysql:3306
MYSQL_DATABASE=dts
MYSQL_USER=dts
MYSQL_USER_PASS=secret

# Optional exclusions
GITLAB_EXCLUDED_PROJECT_IDS=1,2,3
GITLAB_EXCLUDED_USER_IDS=10,20
GIT_LOG_EXCLUDE_PATH=vendor,node_modules
```

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

The interactive menu provides options to:
- Sync GitLab data (projects, users, merge requests, commits, events, labels)
- Generate developer reports (Markdown table or HTML)
- View statistics

### HTML Reports

HTML reports are saved to the `reports/` directory with a timestamp in the filename. Reports feature an interactive sortable table with top-3 developer highlighting.

### Code Quality

Run all checks:
```bash
make code_quality
```

Individual checks:
```bash
make phpcs          # Check code style (PSR-12 + Slevomat)
make phpcbf         # Auto-fix code style issues
make phpstan        # Static analysis (level 5)
make rector         # Rector dry-run
make rectorbf       # Apply Rector fixes
```

### Testing

```bash
make test                            # Run all tests
make test_filter filter=TestClass    # Run specific test
```

### Database Migrations

Migrations are managed with Phinx (`app/phinx/dts/migrations/`):

```bash
make phinx_create name=MigrationName   # Create migration
make phinx_migrate                     # Run migrations
make phinx_rollback                    # Rollback last migration
```

## Scoring System

DTS uses a configurable points-based scoring system to evaluate developer contributions. The final score for each developer is a **weighted sum** of all metrics:

```
Score = (MRs Created x W1) + (Approvals Given x W2) + (MRs Merged x W3) +
        (MRs Merged with Approval x W4) + (MRs Tested x W5) +
        (Lines Added x W6) + (Lines Deleted x W7) +
        (Self-Approvals x W8) + (Direct Commits x W9)
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
├── Application/           # Use cases - orchestrate domain logic
│   ├── Cli/               # ExitUseCase, MenuUseCase
│   ├── Common/            # Paginator
│   ├── Gitlab/            # Sync* use cases for each GitLab entity
│   └── Report/            # DevReportUseCase, DevReportPresenterInterface,
│                          # ScoredDeveloper, ReportDateProviderInterface
├── Domain/                # Pure business logic, no framework dependencies
│   ├── Common/            # Abstract value objects (string, int, date, url)
│   ├── Git/               # Git entities (Commit, Project, Stats, User)
│   ├── Gitlab/            # GitLab entities per resource type
│   │   ├── Commit/        # Commits with CommitGitCommitId
│   │   ├── CommitStats/   # Commit statistics (additions/deletions)
│   │   ├── Event/         # GitLab events
│   │   ├── Label/         # Labels
│   │   ├── MergeRequest/  # Merge requests
│   │   ├── Note/          # MR notes/comments
│   │   ├── Project/       # Projects with default branch info
│   │   ├── PushData/      # Push event data
│   │   ├── ResourceLabelEvent/  # Label change events
│   │   ├── User/          # GitLab users
│   │   └── Common/        # Shared GitLab interfaces & value objects
│   └── Report/            # Developer report domain
│       ├── DeveloperStatistics         # Readonly DTO with 11 metrics
│       ├── DeveloperStatisticsCollection
│       ├── ScoringConfiguration        # 9 scoring weights/penalties
│       ├── ScoringService              # Weighted sum calculation
│       ├── ReportCriteria              # Start date + tested labels
│       ├── Repository/                 # DevReportRepositoryInterface
│       └── ValueObject/                # ScoringWeight, ScoringPenalty,
│                                       # MergeRequestCount, ApprovalCount,
│                                       # CommitCount, LineCount, LabelName, etc.
├── Infrastructure/        # External integrations & implementations
│   ├── Gitlab/            # GitlabApiClient, API + MySQL repositories
│   ├── Git/               # GitRepository
│   └── Report/            # DevReportMySqlRepository
└── Presentation/          # Entry points and configuration
    ├── Cli/               # Interactive menu (Cli, Command enum,
    │                      # StdoutSyncOutput, CommandNotFoundException)
    ├── Config/            # DI container configuration
    │   ├── AppConfiguration      # MySQL env config
    │   ├── GitlabConfiguration   # GitLab + scoring env config
    │   ├── main.php              # MySQL PDO setup
    │   ├── cli.php               # CLI command wiring
    │   ├── gitlab.php            # GitLab + Report wiring
    │   └── bootstrap.php         # DI container assembly
    └── Report/            # Report presenters
        ├── DevReportTablePresenter   # Markdown table output
        ├── DevReportHtmlPresenter    # Interactive HTML report
        └── CliReportDateProvider     # CLI date input
```

### Key Principles

- **Repository Pattern**: each entity has API repository (fetch from GitLab) and MySQL repository (local storage)
- **Dependency Inversion**: domain interfaces defined in Domain layer, implemented in Infrastructure
- **Use Case Pattern**: each business operation is a separate class implementing `UseCaseInterface`
- **DI Container**: PHP-DI configured in `Presentation/Config/` — all dependencies injected, no static coupling
- **Value Objects**: rich domain types with validation (counts, weights, penalties, dates, IDs)
- **Configuration encapsulation**: `AppConfiguration` and `GitlabConfiguration` wrap environment variables

## Development

### Code Standards

- PHP 8.4 with strict types (`declare(strict_types=1)`)
- PSR-12 + Slevomat coding standard
- Single quotes for strings
- camelCase for variables and methods
- Class constants must have visibility and type hints
- Cyclomatic complexity: max 5 (absolute 7)
- Cognitive complexity: max 8
- Nesting level: max 2 (absolute 3)

### Tools

- PHP_CodeSniffer with PSR-12 + Slevomat Coding Standard
- PHPStan level 5
- Rector for code modernization
- PHPUnit for testing

## Project Structure

```
dts/
├── app/
│   ├── src/              # Application source code
│   ├── phinx/            # Database migrations
│   ├── cli.php           # CLI entry point
│   └── composer.json      # PHP dependencies
├── docker/               # Docker configuration (PHP, MySQL)
├── docker-compose.yml    # Docker services (PHP, MySQL)
├── Makefile              # Convenience commands
├── reports/              # Generated HTML reports
├── projects/             # Local Git repositories (for analysis)
├── sql/                  # SQL scripts
└── tests/                # PHPUnit tests
```

## Technology Stack

- **PHP 8.4** — Main language
- **MySQL 8.0** — Database
- **GitLab API** — Data source (via Guzzle HTTP client)
- **PHP-DI** — Dependency injection container
- **Symfony Console** — CLI components
- **Symfony Dotenv** — Environment configuration
- **Phinx** — Database migrations
- **PHPUnit** — Testing
- **PHPStan** — Static analysis
- **PHP_CodeSniffer** — Code style (PSR-12 + Slevomat)
- **Rector** — Code modernization
- **Docker** — Containerization
