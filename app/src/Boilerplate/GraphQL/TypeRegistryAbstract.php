<?php

namespace App\Boilerplate\GraphQL;

use App\Boilerplate\GraphQL\Exception\UnknownTypeException;

/**
 * Class TypeRegistryAbstract
 * @package App\Boilerplate\GraphQL
 */
abstract class TypeRegistryAbstract
{

    /** @var bool  */
    public $isLazyLoadingGraphqlTypes = true;

    /** @var array  */
    protected $types = [];

    /**
     * @param $classname
     * @return mixed|null
     * @throws UnknownTypeException
     */
    protected function byClassName($classname)
    {
        $cacheName = end(explode('\\', $classname));
        $type = null;

        if (!isset($this->types[$cacheName])) {
            if (class_exists($classname)) {
                $type = new $classname();
            }

            $this->types[$cacheName] = $type;
        }

        $type = $this->types[$cacheName];

        if (!$type) {
            throw new UnknownTypeException($classname);
        }
        return $type;
    }

    /**
     * @param $shortName
     * @return mixed|null
     * @throws UnknownTypeException
     */
    public function byTypeName($shortName)
    {
        $cacheName = ($shortName);
        $type = null;

        if (isset($this->types[$cacheName])) {
            return $this->types[$cacheName];
        }

        $method = ($shortName);
        if(method_exists(get_called_class(), $method)) {
            $type = $this->{$method}();
        }

        if(!$type) {
            throw new UnknownTypeException($shortName);
        }
        return $type;
    }

    /**
     * @param $classname
     * @return \Closure|mixed|null
     * @throws UnknownTypeException
     */
    public function get($classname)
    {
        return $this->isLazyLoadingGraphqlTypes ? function() use ($classname) {
            return $this->byClassName($classname);
        } : $this->byClassName($classname);
    }

    /**
     * is triggered when invoking inaccessible methods in an object context.
     *
     * @param $name string
     * @param $arguments array
     * @return mixed
     * @throws UnknownTypeException
     * @link https://php.net/manual/en/language.oop5.overloading.php#language.oop5.overloading.methods
     */
    public function __call($name, $arguments)
    {
        return $this->byTypeName($name);
    }
}
