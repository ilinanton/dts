<?php

namespace App\Domain\GitLab\Commit;

use App\Domain\GitLab\Commit\ValueObject\CommitAuthoredDate;
use App\Domain\GitLab\Commit\ValueObject\CommitAuthorEmail;
use App\Domain\GitLab\Commit\ValueObject\CommitAuthorName;
use App\Domain\GitLab\Commit\ValueObject\CommitCommittedDate;
use App\Domain\GitLab\Commit\ValueObject\CommitCommitterEmail;
use App\Domain\GitLab\Commit\ValueObject\CommitCommitterName;
use App\Domain\GitLab\Commit\ValueObject\CommitCreatedAt;
use App\Domain\GitLab\Commit\ValueObject\CommitId;
use App\Domain\GitLab\Commit\ValueObject\CommitMessage;
use App\Domain\GitLab\Commit\ValueObject\CommitShortId;
use App\Domain\GitLab\Commit\ValueObject\CommitStats;
use App\Domain\GitLab\Commit\ValueObject\CommitTitle;
use App\Domain\GitLab\Commit\ValueObject\CommitWebUrl;
use App\Domain\GitLab\Common\AbstractEntity;

final readonly class Commit extends AbstractEntity
{
    public function __construct(
        private CommitId $id,
        private CommitShortId $shortId,
        private CommitTitle $title,
        private CommitMessage $message,
        private CommitCreatedAt $createdAt,
        private CommitWebUrl $webUrl,
        private CommitAuthorName $authorName,
        private CommitAuthorEmail $authorEmail,
        private CommitAuthoredDate $authoredDate,
        private CommitCommitterName $committerName,
        private CommitCommitterEmail $committerEmail,
        private CommitCommittedDate $committedDate,
        private CommitStats $stats,
    ) {
    }

    public function getId(): CommitId
    {
        return $this->id;
    }

    public function getShortId(): CommitShortId
    {
        return $this->shortId;
    }

    public function getTitle(): CommitTitle
    {
        return $this->title;
    }

    public function getMessage(): CommitMessage
    {
        return $this->message;
    }

    public function getCreatedAt(): CommitCreatedAt
    {
        return $this->createdAt;
    }

    public function getWebUrl(): CommitWebUrl
    {
        return $this->webUrl;
    }

    public function getAuthorName(): CommitAuthorName
    {
        return $this->authorName;
    }

    public function getAuthorEmail(): CommitAuthorEmail
    {
        return $this->authorEmail;
    }

    public function getAuthoredDate(): CommitAuthoredDate
    {
        return $this->authoredDate;
    }

    public function getCommitterName(): CommitCommitterName
    {
        return $this->committerName;
    }

    public function getCommitterEmail(): CommitCommitterEmail
    {
        return $this->committerEmail;
    }

    public function getCommittedDate(): CommitCommittedDate
    {
        return $this->committedDate;
    }

    public function getStats(): CommitStats
    {
        return $this->stats;
    }
}
