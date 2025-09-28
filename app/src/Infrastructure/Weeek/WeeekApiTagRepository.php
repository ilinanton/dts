<?php

declare(strict_types=1);

namespace App\Infrastructure\Weeek;

use App\Domain\Weeek\Common\Repository\WeeekApiClientInterface;
use App\Domain\Weeek\Tag\Factory\TagCollectionFromArray;
use App\Domain\Weeek\Tag\Repository\WeeekApiTagRepositoryInterface;
use App\Domain\Weeek\Tag\TagCollection;
use Exception;

final readonly class WeeekApiTagRepository implements WeeekApiTagRepositoryInterface
{
    public function __construct(
        private WeeekApiClientInterface $client,
    ) {
    }

    public function get(): TagCollection
    {
        $data = $this->client->getWorkspaceTags();
        if (true !== $data['success']) {
            throw new Exception($data['message']);
        }

        $factory = new TagCollectionFromArray($data['tags']);
        return $factory->create();
    }
}
