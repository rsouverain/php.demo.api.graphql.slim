<?php

namespace App\Boilerplate\GraphQL;

abstract class TypeRegistryAbstract
{

    protected static function byClassName($classname) {
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

    public static function get($classname)
    {
        return static::LAZY_LOAD_GRAPHQL_TYPES ? function() use ($classname) {
            return static::byClassName($classname);
        } : static::byClassName($classname);
    }
}
