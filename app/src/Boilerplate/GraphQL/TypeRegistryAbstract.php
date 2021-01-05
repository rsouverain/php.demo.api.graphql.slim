<?php

namespace App\Boilerplate\GraphQL;

/**
 * Class TypeRegistryAbstract
 * @package App\Boilerplate\GraphQL
 */
abstract class TypeRegistryAbstract
{

    /** @var array  */
    protected static $types = [];

    /**
     * @param $classname
     * @return mixed|null
     * @throws \Exception
     */
    protected static function byClassName($classname)
    {
        $parts = explode('\\', $classname);
        $cacheName = strtolower(preg_replace('~Type$~', '', $parts[count($parts) - 1]));
        $type = null;

        if (!isset(self::$types[$cacheName])) {
            if (class_exists($classname)) {
                $type = new $classname();
            }

            self::$types[$cacheName] = $type;
        }

        $type = self::$types[$cacheName];

        if (!$type) {
            throw new \Exception('Unknown graphql type: ' . $classname);
        }
        return $type;
    }

    /**
     * @param $shortName
     * @param bool $removeType
     * @return mixed|null
     * @throws \Exception
     */
    public static function byTypeName($shortName, $removeType=true)
    {
        $cacheName = strtolower($shortName);
        $type = null;

        if (isset(self::$types[$cacheName])) {
            return self::$types[$cacheName];
        }

        $method = lcfirst($shortName);
        if(method_exists(get_called_class(), $method)) {
            $type = self::{$method}();
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
    public static function get($classname)
    {
        return static::LAZY_LOAD_GRAPHQL_TYPES ? function() use ($classname) {
            return static::byClassName($classname);
        } : static::byClassName($classname);
    }
}
