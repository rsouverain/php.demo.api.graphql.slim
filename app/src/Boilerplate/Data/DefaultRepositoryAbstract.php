<?php

namespace App\Boilerplate\Data;

use App\Boilerplate\GraphQL\Endpoint;
use App\Boilerplate\GraphQL\Exception\DataLoaderNotFoundException;
use App\Boilerplate\GraphQL\Exception\InvalidDataloaderResultCountException;
use GraphQL\Executor\Promise\Adapter\SyncPromiseAdapter;
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
        // We are building DataLoaders...
        if (self::$dataLoaderPromiseAdapter === null) {
            self::$dataLoaderPromiseAdapter = Endpoint::$promiseAdapter;
        }
        if (self::$dataLoaderPromiseAdapter === null) {
            self::$dataLoaderPromiseAdapter = new WebonyxGraphQLSyncPromiseAdapter(new SyncPromiseAdapter());
        }
        foreach ($dataLoaderIdentifiers as $dataLoaderIdentifier) {
            $this->dataLoaders[$dataLoaderIdentifier] = new DataLoader(
                 function ($keys) use ($dataLoaderIdentifier) {
                     return self::$dataLoaderPromiseAdapter->createAll(
                         call_user_func([$this, "fetchByProperties"], $keys, $dataLoaderIdentifier)
                     );
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
