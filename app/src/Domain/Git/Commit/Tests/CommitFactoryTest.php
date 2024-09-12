<?php

namespace App\Domain\Git\Commit\Tests;

use App\Domain\Git\Commit\CommitFactory;
use PHPUnit\Framework\TestCase;

final class CommitFactoryTest extends TestCase
{
    public static function commitsData(): array
    {
        return [
            ["{|p|}commit: ec3fee94045ea63bb4ca36592fac912daa9823fc{|p|}date: 2024-01-10T09:04:22+00:00{|p|}email: Bot@users.noreply.github.com{|p|}stat:   1 file changed, 8 insertions(+) "],
            ["{|p|}commit: 5c0cbe82d94935544529b6383a56d6e6d9321d43{|p|}date: 2024-01-09T18:42:03+05:00{|p|}email: ibragimov@lpmotor.ru{|p|}stat:   4 files changed, 23 insertions(+), 26 deletions(-) "],
            ["{|p|}commit: 2a4d730433f29174a04e0b242acd2e06a9f24c89{|p|}date: 2024-01-09T15:52:25+05:00{|p|}email: ibragimov@lpmotor.ru{|p|}stat:   1 file changed, 1 insertion(+), 1 deletion(-) "],
            ["{|p|}commit: 60dc9ebf002bf7565a767ef78ca124996e4b17d9{|p|}date: 2024-01-09T09:51:35+00:00{|p|}email: Bot@users.noreply.github.com{|p|}stat:   1 file changed, 67 insertions(+) "],
            ["{|p|}commit: 64577bea147de7c0dda43a3fa59df9e18f18decd{|p|}date: 2024-01-09T13:59:38+05:00{|p|}email: permjakov-am@yandex.ru{|p|}stat:   1 file changed, 1 insertion(+), 1 deletion(-) "],
        ];
    }

    /**
     * @dataProvider commitsData
     */
    public function testParseCommitId(string $commitData): void
    {
        $commitFactory = new CommitFactory();
        $commitId = $commitFactory->parseCommitId($commitData);
        $this->assertTrue(32 === strlen($commitId->getValue()));
        $this->assertStringContainsString($commitId->getValue(), $commitData);
    }

    /**
     * @dataProvider commitsData
     */
    public function testParseCommitAuthoredDate(string $commitData): void
    {
        $commitFactory = new CommitFactory();
        $commitAuthoredDate = $commitFactory->parseCommitAuthoredDate($commitData);

        $this->assertStringContainsString(
            ltrim($commitAuthoredDate->getValueInMainFormat(), '+'),
            $commitData
        );
    }
}
