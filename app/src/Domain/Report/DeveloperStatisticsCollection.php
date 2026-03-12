<?php

declare(strict_types=1);

namespace App\Domain\Report;

use App\Domain\Common\AbstractCollection;

/** @extends AbstractCollection<DeveloperStatistics> */
final class DeveloperStatisticsCollection extends AbstractCollection
{
    protected function getType(): string
    {
        return DeveloperStatistics::class;
    }

    /**
     * @param array<float> $scores
     * @return array{collection: self, scores: array<float>}
     */
    public function sortByScoreDescending(array $scores): array
    {
        $items = iterator_to_array($this);
        array_multisort($scores, SORT_DESC, $items);

        $sorted = new self();
        foreach ($items as $item) {
            $sorted->add($item);
        }

        return ['collection' => $sorted, 'scores' => $scores];
    }
}
