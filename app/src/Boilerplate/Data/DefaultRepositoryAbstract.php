<?php

namespace App\Boilerplate\Data;

use App\Boilerplate\GraphQL\Exception\DataLoaderNotFoundException;
use App\Boilerplate\GraphQL\Exception\InvalidDataloaderResultCountException;
use App\GraphQL\Schema\Blog\Domain\Repository\User\UserRepository;
use GraphQL\Executor\Promise\Adapter\SyncPromiseAdapter;
use GraphQL\Executor\Promise\Promise;
use GraphQL\GraphQL;
use Overblog\DataLoader\DataLoader;
use Overblog\PromiseAdapter\Adapter\WebonyxGraphQLSyncPromiseAdapter;

abstract class DefaultRepositoryAbstract implements DefaultRepositoryInterface
{
    /** @var WebonyxGraphQLSyncPromiseAdapter  */
    protected static $dataLoaderPromiseAdapter;

    /** @var array  */
    protected $dataLoaders = [];

    public function __construct(array $dataLoaderIdentifiers)
    {
        // We are building DataLoader
        /*GraphQL::setPromiseAdapter();*/
        $graphQLPromiseAdapter = new SyncPromiseAdapter();
        if (self::$dataLoaderPromiseAdapter === null) {
            self::$dataLoaderPromiseAdapter = new WebonyxGraphQLSyncPromiseAdapter($graphQLPromiseAdapter);
            GraphQL::setPromiseAdapter($graphQLPromiseAdapter);
        }
        foreach ($dataLoaderIdentifiers as $dataLoaderIdentifier) {
            $this->dataLoaders[$dataLoaderIdentifier] = new DataLoader(
                 function ($keys) use ($dataLoaderIdentifier, $graphQLPromiseAdapter) {
                     $promise = new SyncPromiseAdapter(function () use (&$promise, $keys, $dataLoaderIdentifier) {
                        return call_user_func([$this, "fetchByProperties"], $keys, $dataLoaderIdentifier);
                     }, self::$dataLoaderPromiseAdapter);
                     return $promise;
                 },
                self::$dataLoaderPromiseAdapter
            );
        }
    }

    private static $instance;
    public static function getInstance ()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param string $propertyName
     * @return DataLoader
     * @throws DataLoaderNotFoundException
     */
    public function getDataLoader(string $propertyName = 'id'): DataLoader
    {
        if (
            isset($this->dataLoaders[$propertyName]) &&
            $this->dataLoaders[$propertyName] instanceof DataLoader
        ) {
            return $this->dataLoaders[$propertyName];
        }
        else {
            throw new DataLoaderNotFoundException;
        }
    }

    /**
     * @param array $datas
     * @param array $keys
     * @param string $propertyName
     * @return array
     * @throws InvalidDataloaderResultCountException
     */
    protected function _filterDataByProperties(array $datas, array $keys, $propertyName = 'id'): array
    {
        $results = [];
        foreach ($keys as $key) {
            $results = array_filter($datas, function($item) use ($key, $propertyName) {
                if (isset($item[$propertyName])) {
                    return $item[$propertyName] === $key;
                }
                return false;
            });
        }
        if (count($results) !== count($keys)) {
            throw new InvalidDataloaderResultCountException();
        }
        return $results;
    }

}
