<?php

namespace App\GraphQL\Schema\Blog\Domain\Services\User;

use GraphQL\Executor\Promise\Adapter\SyncPromiseAdapter;
use GraphQL\GraphQL;
use App\GraphQL\Schema\Blog\Domain\Repository\User\UserRepository;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;

class UserService
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct()
    {
        $graphQLPromiseAdapter = new SyncPromiseAdapter();
        $dataLoaderPromiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($graphQLPromiseAdapter);
        $this->userRepository = new UserRepository($dataLoaderPromiseAdapter);
        GraphQL::setPromiseAdapter($graphQLPromiseAdapter);
    }


    /**
     * @param integer $id
     * @return User|null
     */
    public function findUser($userId)
    {
        return $this->userRepository->load($userId);
    }

}