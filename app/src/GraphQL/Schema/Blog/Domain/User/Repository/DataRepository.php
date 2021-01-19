<?php

namespace App\GraphQL\Schema\Blog\Domain\Repository;

use Overblog\DataLoader\DataLoader;
use Overblog\DataLoader\Option;
use Overblog\PromiseAdapter\PromiseAdapterInterface;

class DataRepository extends DataLoader
{
    /** @var Array */
    protected $searchKeys = ['id', 'email'];


    public function __construct(callable $batchLoadFn, PromiseAdapterInterface $promiseFactory, Option $options = null)
    {
        parent::__construct($batchLoadFn, $promiseFactory, $options);
    }

    protected function loadByKey() {
        return (function (array $keys) {
            foreach ($this->searchKeys as $key => $values) {
                $method = 'loadBy' . ucfirst($key);
                if (method_exists($this, $method)) {
                    foreach ($keys as $value) {
                        $res[] =  $this->{$method}($value);
                    }
                }
            }
        });
    }
}