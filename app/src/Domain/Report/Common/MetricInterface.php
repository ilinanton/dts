<?php

namespace App\Domain\Report\Common;

interface MetricInterface
{
    public function getName(): string;
    public function getDescription(): string;
    public function getType(): string;
}
