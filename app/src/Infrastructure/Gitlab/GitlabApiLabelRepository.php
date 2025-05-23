<?php

declare(strict_types=1);

namespace App\Infrastructure\Gitlab;

use App\Domain\Gitlab\Common\Repository\GitlabApiClientInterface;
use App\Domain\Gitlab\Label\LabelCollection;
use App\Domain\Gitlab\Label\LabelFactory;
use App\Domain\Gitlab\Label\Repository\GitlabApiLabelRepositoryInterface;

final readonly class GitlabApiLabelRepository implements GitlabApiLabelRepositoryInterface
{
    public function __construct(
        private GitlabApiClientInterface $client,
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
