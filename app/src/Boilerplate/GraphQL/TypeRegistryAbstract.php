<?php

namespace App\Boilerplate\GraphQL;

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
     * @throws \Exception
     */
    protected function byClassName($classname)
    {
        $parts = explode('\\', $classname);
        $cacheName = strtolower(preg_replace('~Type$~', '', $parts[count($parts) - 1]));
        $type = null;

        if (!isset($this->types[$cacheName])) {
            if (class_exists($classname)) {
                $type = new $classname();
            }

            $this->types[$cacheName] = $type;
        }

        $type = $this->types[$cacheName];

        if (!$type) {
            throw new \Exception('Unknown graphql type: ' . $classname);
        }
        return $type;
    }

    /**
     * @param $shortName
     * @return mixed|null
     * @throws \Exception
     */
    public function byTypeName($shortName)
    {
        $cacheName = strtolower($shortName);
        $type = null;

        if (isset($this->types[$cacheName])) {
            return $this->types[$cacheName];
        }

        $method = lcfirst($shortName);
        if(method_exists(get_called_class(), $method)) {
            $type = $this->{$method}();
        }

        if(!$type) {
            throw new \Exception("Unknown graphql type: " . $shortName);
        }
        return $type;
    }

    /**
     * @param $classname
     * @return \Closure|mixed|null
     * @throws \Exception
     */
    public function get($classname)
    {
        return $this->isLazyLoadingGraphqlTypes ? function() use ($classname) {
            return $this->byClassName($classname);
        } : $this->byClassName($classname);
    }
}
