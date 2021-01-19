<?php

namespace App\GraphQL\Schema\demo\User;

use App\Boilerplate\AppContext;
use App\Boilerplate\GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Schema\_common\Data\UserAccount\Repository\UserAccountRepository;
use App\GraphQL\Schema\demo\TypeRegistry;
use GraphQL\Executor\Promise\Promise;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\DataLoader\DataLoader;

class UserAccount extends ObjectType
{
    public function __construct()
    {
        $types = TypeRegistry::getInstance();

        $config = [
            'name' => 'UserAccount',
            'description' => 'Our blog authors',
            'interfaces' => [
                $types->DataNodeInterface(),
            ],
            'fields' => [
                'id' => [
                    'type' => $types::nonNull($types::id()),
                    'description' => 'User\'s unique identifier',
                ],
                'login' => [
                    'type' => $types::string(),
                    'description' => 'User\'s login handler',
                ],
                'email' => [
                    'type' => $types->Email(),
                    'description' => 'User\'s email address',
                ],
                'firstName' => [
                    'type' => $types::string(),
                ],
                'lastName' => [
                    'type' => $types::string(),
                ],
                '_isMe' => [
                    'type' => $types::boolean(),
                    'description' => '`true` if your are authenticated as this user',
                    'resolve' => function ($objectValue, $args, AppContext $context, ResolveInfo $info) {
                        $authed = $context->getAuthenticatedUserAccount();
                        return $authed !== null && $objectValue !== null && $authed['id'] === $objectValue['id'];
                    },
                ],
            ],
        ];
        parent::__construct($config);
    }

    /**
     * @param array $keys
     * @return array
     * @throws \App\Boilerplate\GraphQL\Exception\InvalidDataloaderResultCountException
     */
    public function fetchByIds(array $keys) {
        return UserAccountRepository::getInstance()
            ->getDataLoader()
            ->loadMany($keys);
        ;
    }

    /**
     * @param string $key
     * @return array
     * @throws \App\Boilerplate\GraphQL\Exception\InvalidDataloaderResultCountException
     */
    public function fetchById(string $key) {
        $promise = UserAccountRepository::getInstance()
            ->getDataLoader()
            ->load($key)
        ;
        return DataLoader::await($promise);
    }

}
