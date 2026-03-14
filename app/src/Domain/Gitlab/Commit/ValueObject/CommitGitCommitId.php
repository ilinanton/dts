<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Commit\ValueObject;

use App\Domain\Common\ValueObject\AbstractRequiredString;
use InvalidArgumentException;

final readonly class CommitGitCommitId extends AbstractRequiredString
{
    private const int EXPECTED_LENGTH = 32;

    public function __construct(string $value)
    {
        parent::__construct($value);
        $this->assertLength($value);
    }

    /**
     * Creates CommitGitCommitId from a full 40-character GitLab SHA
     * by truncating it to the first 32 characters used for matching with local Git commits.
     */
    public static function fromFullSha(string $fullSha): self
    {
        return new self(substr($fullSha, 0, self::EXPECTED_LENGTH));
    }

    private function assertLength(string $value): void
    {
        if (strlen($value) !== self::EXPECTED_LENGTH) {
            throw new InvalidArgumentException(
                self::class . ' must be exactly ' . self::EXPECTED_LENGTH . ' characters, got ' . strlen($value)
            );
        }
    }
}
