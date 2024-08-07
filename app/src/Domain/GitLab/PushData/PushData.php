<?php

namespace App\Domain\GitLab\PushData;

final class PushData
{
    private $commitCount;
    private $action;
    private $refType;
    private $commitFrom;
    private $commitTo;
    private $ref;
    private $commitTitle;
    private $refCount;
}
