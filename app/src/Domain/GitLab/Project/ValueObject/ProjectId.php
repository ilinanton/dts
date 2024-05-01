<?php

namespace App\Domain\GitLab\Project\ValueObject;

final class ProjectId
{
    private int $value;

    public function __construct(int $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    private function assertValueIsValid(int $value): void
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Id is incorrect!');
        }
    }
}
