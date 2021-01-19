<?php

namespace App\GraphQL\Schema\_common\Data\UserAccount\Repository;

use App\Boilerplate\Data\DefaultRepositoryAbstract;
use App\Boilerplate\GraphQL\Exception\InvalidDataloaderResultCountException;

class UserAccountRepository extends DefaultRepositoryAbstract
{
    public function fetchAll ()
    {
        return json_decode(file_get_contents(realpath(__DIR__.'/../UserAccounts.json')), true); // don't do this in real life, file system is usually not really fast
    }

    /**
     * @param array $ids
     * @param string $identifierName
     * @return array
     * @throws InvalidDataloaderResultCountException
     */
    public function fetchByIdentifiers(array $ids, $identifierName = 'id'): array
    {
        $results = [];
        $accounts = $this->fetchAll(); // don't do this in real life
        foreach ($ids as $id) {
            if (isset($accounts[$id])) {
                $results[$id] = $accounts[$id];
            }
            else {
                $results[$id] = null;
            }
        }

        if (count($results) !== count($ids)) {
            throw new InvalidDataloaderResultCountException();
        }

        return $results;
    }

    /**
     * @param string $id
     * @param string $identifierName
     * @return mixed|null
     * @throws InvalidDataloaderResultCountException
     */
    public function fetchByIdentifier(string $id, $identifierName = 'id')
    {
        $results = $this->fetchByIdentifiers([$id], $identifierName);
        if (is_array($results) && count($results) > 0) {
            return $results[$id];
        }
        return null;
    }
}
