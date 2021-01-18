<?php

namespace App\Boilerplate\Data;

interface DefaultRepositoryInterface
{
    /**
     * @param array $ids
     * @param string $identifierName
     * @return array
     */
    public function fetchByIdentifiers (array $ids, string $identifierName = 'id') : array ;

    /**
     * @param string $id
     * @param string $identifierName
     * @return mixed|null
     */
    public function fetchByIdentifier (string $id, string $identifierName = 'id');
}
