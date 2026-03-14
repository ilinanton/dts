<?php

declare(strict_types=1);

namespace App\Domain\Gitlab\Commit\Tests;

use App\Domain\Gitlab\Commit\ValueObject\CommitGitCommitId;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class CommitGitCommitIdTest extends TestCase
{
    public function testConstructWithValid32CharValue(): void
    {
        $value = 'ec3fee94045ea63bb4ca36592fac912d';
        $commitGitCommitId = new CommitGitCommitId($value);

        $this->assertSame($value, $commitGitCommitId->value);
    }

    public function testConstructRejectsEmptyString(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CommitGitCommitId('');
    }

    public function testConstructRejectsTooShortValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CommitGitCommitId('ec3fee94045ea63b');
    }

    public function testConstructRejectsTooLongValue(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new CommitGitCommitId('ec3fee94045ea63bb4ca36592fac912daa9823fc');
    }

    public function testFromFullShaTruncatesTo32Characters(): void
    {
        $fullSha = 'ec3fee94045ea63bb4ca36592fac912daa9823fc';
        $commitGitCommitId = CommitGitCommitId::fromFullSha($fullSha);

        $this->assertSame('ec3fee94045ea63bb4ca36592fac912d', $commitGitCommitId->value);
        $this->assertSame(32, strlen($commitGitCommitId->value));
    }

    public function testFromFullShaWith32CharInput(): void
    {
        $sha = 'ec3fee94045ea63bb4ca36592fac912d';
        $commitGitCommitId = CommitGitCommitId::fromFullSha($sha);

        $this->assertSame($sha, $commitGitCommitId->value);
    }
}
