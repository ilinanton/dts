<?php

declare(strict_types=1);

namespace App\Domain\Git\Commit\Tests;

use App\Domain\Git\Commit\CommitFactory;
use PHPUnit\Framework\TestCase;

final class CommitFactoryTest extends TestCase
{
    private const string EMAIL_1 = 's@dd.wqd.fwqfe.com';
    private const string EMAIL_2 = 'wwwww@sssb.com';
    private const string EMAIL_3 = 'weqweqwe@wwwww.ru';
    private const array EMAILS = [
        self::EMAIL_1,
        self::EMAIL_2,
        self::EMAIL_3,
    ];

    private const string NAME_1 = 'Den Brown';
    private const string NAME_2 = 'Ken Lower';
    private const string NAME_3 = 'Phil Selfish';
    private const array NAMES = [
        self::NAME_1,
        self::NAME_2,
        self::NAME_3,
    ];

    public static function commitsData(): array
    {
        return [
            [
                '{|p|}commit: ec3fee94045ea63bb4ca36592fac912daa9823fc{|p|}date: 2024-01-10T09:04:22+00:00' .
                '{|p|}email: ' . self::EMAIL_1 .
                '{|p|}name: ' . self::NAME_1 .
                '{|p|}stat:   1 file changed, 8 insertions(+) ',
            ],
            [
                '{|p|}commit: 5c0cbe82d94935544529b6383a56d6e6d9321d43{|p|}date: 2024-01-09T18:42:03+05:00' .
                '{|p|}email: ' . self::EMAIL_2 .
                '{|p|}name: ' . self::NAME_2 .
                '{|p|}stat:   4 files changed, 23 insertions(+), 26 deletions(-) '
            ],
            [
                '{|p|}commit: 2a4d730433f29174a04e0b242acd2e06a9f24c89{|p|}date: 2024-01-09T15:52:25+05:00' .
                '{|p|}email: ' . self::EMAIL_2 .
                '{|p|}name: ' . self::NAME_2 .
                '{|p|}stat:   1 file changed, 1 insertion(+), 1 deletion(-) ',
            ],
            [
                '{|p|}commit: 60dc9ebf002bf7565a767ef78ca124996e4b17d9{|p|}date: 2024-01-09T09:51:35+00:00' .
                '{|p|}email: ' . self::EMAIL_3 .
                '{|p|}name: ' . self::NAME_3 .
                '{|p|}stat:   1 file changed, 67 insertions(+) ',
            ],
            [
                '{|p|}commit: 64577bea147de7c0dda43a3fa59df9e18f18decd{|p|}date: 2024-01-09T13:59:38+05:00' .
                '{|p|}email: ' . self::EMAIL_3 .
                '{|p|}name: ' . self::NAME_3 .
                '{|p|}stat:   1 file changed, 1 insertion(+), 1 deletion(-) ',
            ],
        ];
    }

    /**
     * @dataProvider commitsData
     */
    public function testParseCommitId(string $commitData): void
    {
        $commitFactory = new CommitFactory();
        $commitId = $commitFactory->parseCommitId($commitData);
        $this->assertTrue(32 === strlen($commitId->value));
        $this->assertStringContainsString($commitId->value, $commitData);
    }

    /**
     * @dataProvider commitsData
     */
    public function testParseAuthorName(string $commitData): void
    {
        $commitFactory = new CommitFactory();
        $authorName = $commitFactory->parseAuthorName($commitData);
        $this->assertContains($authorName->value, self::NAMES);
    }

    /**
     * @dataProvider commitsData
     */
    public function testParseAuthorEmail(string $commitData): void
    {
        $commitFactory = new CommitFactory();
        $authorEmail = $commitFactory->parseAuthorEmail($commitData);
        $this->assertContains($authorEmail->value, self::EMAILS);
    }

    /**
     * @dataProvider commitsData
     */
    public function testParseCommitAuthorDate(string $commitData): void
    {
        $commitFactory = new CommitFactory();
        $commitAuthorDate = $commitFactory->parseCommitAuthorDate($commitData);

        $this->assertStringContainsString(
            ltrim($commitAuthorDate->getValueInMainFormat(), '+'),
            $commitData
        );
    }

    /**
     * @dataProvider commitsData
     */
    public function testParseCommitStats(string $commitData): void
    {
        $commitFactory = new CommitFactory();
        $commitStats = $commitFactory->parseCommitStats($commitData);

        $this->assertTrue($commitStats->value->files->value > 0);
        $this->assertTrue($commitStats->value->additions->value > 0);
        $this->assertTrue($commitStats->value->deletions->value === 0);
    }
}
