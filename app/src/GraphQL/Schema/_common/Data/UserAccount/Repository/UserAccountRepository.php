<?php

namespace App\GraphQL\Schema\_common\Data\UserAccount\Repository;

use App\Boilerplate\Data\DefaultRepositoryAbstract;
use App\Boilerplate\GraphQL\Exception\InvalidDataloaderResultCountException;

class UserAccountRepository extends DefaultRepositoryAbstract
{


    public function __construct()
    {
        parent::__construct([
            'id',
            'email',
        ]);
    }

    public function fetchAll ()
    {
        return json_decode(file_get_contents(realpath(__DIR__.'/../UserAccounts.json')), true); // don't do this in real life, file system is usually not really fast
    }

    /**
     * @param array $keys
     * @param string $propertyName
     * @return array
     * @throws InvalidDataloaderResultCountException
     */
    public function fetchByProperties(array $keys, $propertyName = 'id'): array
    {
        $results = $this->_filterDataByProperties(
            $this->fetchAll(), // don't do this in real life
            $keys,
            $propertyName
        );
        return $results;
    }

    /**
     * @param string $key
     * @param string $propertyName
     * @return mixed|null
     * @throws InvalidDataloaderResultCountException
     */
    public function fetchByProperty(string $key, $propertyName = 'id')
    {
        $results = $this->fetchByProperties([$key], $propertyName);
        if (is_array($results) && count($results) > 0) {
            return $results[$key];
        }
        return null;
    }
}
