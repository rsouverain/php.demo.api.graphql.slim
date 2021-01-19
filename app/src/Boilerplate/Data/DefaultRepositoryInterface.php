<?php

namespace App\Boilerplate\Data;

interface DefaultRepositoryInterface
{
    /**
     * @param array $keys
     * @param string $propertyName
     * @return array
     */
    public function fetchByProperties(array $keys, string $propertyName = 'id') : array ;

    /**
     * @param string $key
     * @param string $propertyName
     * @return mixed|null
     */
    public function fetchByProperty(string $key, string $propertyName = 'id');

}
