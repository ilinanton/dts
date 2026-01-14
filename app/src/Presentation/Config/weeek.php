<?php

declare(strict_types=1);

use App\Application\UseCaseInterface;
use App\Application\Weeek\SyncWeeekTagsUseCase;
use App\Application\Weeek\SyncWeeekUsersUseCase;
use App\Domain\Weeek\Common\Repository\WeeekApiClientInterface;
use App\Domain\Weeek\Tag\Repository\WeeekApiTagRepositoryInterface;
use App\Domain\Weeek\Tag\Repository\WeeekDataBaseTagRepositoryInterface;
use App\Domain\Weeek\User\Repository\WeeekApiUserRepositoryInterface;
use App\Domain\Weeek\User\Repository\WeeekDataBaseUserRepositoryInterface;
use App\Infrastructure\Weeek\WeeekApiClient;
use App\Infrastructure\Weeek\WeeekApiTagRepository;
use App\Infrastructure\Weeek\WeeekApiUserRepository;
use App\Infrastructure\Weeek\WeeekMySqlTagRepository;
use App\Infrastructure\Weeek\WeeekMySqlUserRepository;
use Psr\Container\ContainerInterface;

return [
    'WEEEK_URL' => $_ENV['WEEEK_URL'],
    'WEEEK_TOKEN' => $_ENV['WEEEK_TOKEN'],


    WeeekApiClientInterface::class => function (ContainerInterface $c): WeeekApiClientInterface {
        return new WeeekApiClient(
            $c->get('WEEEK_URL'),
            $c->get('WEEEK_TOKEN'),
        );
    },

    SyncWeeekUsersUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncWeeekUsersUseCase(
            $c->get(WeeekApiUserRepositoryInterface::class),
            $c->get(WeeekDataBaseUserRepositoryInterface::class),
        );
    },
    WeeekApiUserRepositoryInterface::class => function (ContainerInterface $c): WeeekApiUserRepositoryInterface {
        return new WeeekApiUserRepository(
            $c->get(WeeekApiClientInterface::class),
        );
    },
    WeeekDataBaseUserRepositoryInterface::class => function (ContainerInterface $c): WeeekDataBaseUserRepositoryInterface {
        return new WeeekMySqlUserRepository(
            $c->get(PDO::class),
        );
    },

    SyncWeeekTagsUseCase::class => function (ContainerInterface $c): UseCaseInterface {
        return new SyncWeeekTagsUseCase(
            $c->get(WeeekApiTagRepositoryInterface::class),
            $c->get(WeeekDataBaseTagRepositoryInterface::class),
        );
    },
    WeeekApiTagRepositoryInterface::class => function (ContainerInterface $c): WeeekApiTagRepositoryInterface {
        return new WeeekApiTagRepository(
            $c->get(WeeekApiClientInterface::class),
        );
    },
    WeeekDataBaseTagRepositoryInterface::class => function (ContainerInterface $c): WeeekDataBaseTagRepositoryInterface {
        return new WeeekMySqlTagRepository(
            $c->get(PDO::class),
        );
    },
];
