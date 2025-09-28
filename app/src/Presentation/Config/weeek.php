<?php

declare(strict_types=1);

use App\Application\UseCaseInterface;
use App\Application\Weeek\SyncWeeekUsersUseCase;
use App\Domain\Weeek\Common\Repository\WeeekApiClientInterface;
use App\Domain\Weeek\User\Repository\WeeekApiUserRepositoryInterface;
use App\Infrastructure\Weeek\WeeekApiClient;
use App\Infrastructure\Weeek\WeeekApiUserRepository;
use Psr\Container\ContainerInterface;

return [
    'WEEEK_URL' => $_ENV['WEEEK_URL'],
    'WEEEK_TOKEN' => $_ENV['WEEEK_TOKEN'],

    SyncWeeekUsersUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncWeeekUsersUseCase(
            $c->get(WeeekApiUserRepositoryInterface::class),
        );
    },

    WeeekApiUserRepositoryInterface::class => function (ContainerInterface $c): WeeekApiUserRepositoryInterface {
        return new WeeekApiUserRepository(
            $c->get(WeeekApiClientInterface::class),
        );
    },

    WeeekApiClientInterface::class => function (ContainerInterface $c): WeeekApiClientInterface {
        return new WeeekApiClient(
            $c->get('WEEEK_URL'),
            $c->get('WEEEK_TOKEN'),
        );
    },
];
