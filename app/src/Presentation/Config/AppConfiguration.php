<?php

declare(strict_types=1);

namespace App\Presentation\Config;

final readonly class AppConfiguration
{
    public function __construct(
        public string $mysqlUrl,
        public string $mysqlDatabase,
        public string $mysqlUser,
        public string $mysqlUserPass,
    ) {
    }

    /** @param array<string, mixed> $env */
    public static function fromEnv(array $env): self
    {
        return new self(
            mysqlUrl: (string)$env['MYSQL_URL'],
            mysqlDatabase: (string)$env['MYSQL_DATABASE'],
            mysqlUser: (string)$env['MYSQL_USER'],
            mysqlUserPass: (string)$env['MYSQL_USER_PASS'],
        );
    }
}
