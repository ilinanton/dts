<?php

namespace App\Domain\GitLab\Project\ValueObject;

class ProjectDefaultBranch
{
    private string $value;

    public function __construct(string $value)
    {
        $this->assertValueIsValid($value);
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    private function assertValueIsValid(string $value): void
    {
        if (0 === strlen($value)) {
            throw new \InvalidArgumentException('Default branch is empty!');
        }
    }
}
