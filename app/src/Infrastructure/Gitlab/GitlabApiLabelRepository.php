<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Source\GitlabSourceLabelInterface;
use App\Domain\Gitlab\Label\LabelCollection;
use App\Domain\Gitlab\Label\LabelFactory;
use App\Domain\Gitlab\Label\Repository\GitlabSourceLabelRepositoryInterface;

final readonly class GitlabApiLabelRepository implements GitlabSourceLabelRepositoryInterface
{
    public function __construct(
        private GitlabSourceLabelInterface $client,
    ) {
    }

    public function get(array $params = []): LabelCollection
    {
        $data = $this->client->getLabels($params);
        return $this->createCollection($data);
    }

    private function createCollection(array $data): LabelCollection
    {
        $collection = new LabelCollection();
        $factory = new LabelFactory();
        foreach ($data as $item) {
            $collection->add($factory->create($item));
        }

        return $collection;
    }
}
