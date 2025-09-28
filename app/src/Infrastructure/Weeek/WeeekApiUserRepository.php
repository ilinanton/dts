<?php

declare(strict_types=1);

namespace App\Infrastructure\Weeek;

use App\Domain\Weeek\Common\Repository\WeeekApiClientInterface;
use App\Domain\Weeek\User\Factory\UserCollectionFromArray;
use App\Domain\Weeek\User\Repository\WeeekApiUserRepositoryInterface;
use App\Domain\Weeek\User\UserCollection;
use Exception;

final readonly class WeeekApiUserRepository implements WeeekApiUserRepositoryInterface
{
    public function __construct(
        private WeeekApiClientInterface $client,
    ) {
    }

    public function get(): UserCollection
    {
        $data = $this->client->getWorkspaceMembers();
        if (true !== $data['success']) {
            throw new Exception($data['message']);
        }

        $userCollectionFactory = new UserCollectionFromArray($data['members']);
        return $userCollectionFactory->create();
    }
}
