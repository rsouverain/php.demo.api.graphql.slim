# Endpoint

## Namespace

`App\Boilerplate\Endpoint`

## Description

This class is an utility giving the developer an actionnable API to create a graphql-php schema resolving endpoint.
It was built to wrap-up the common code required to resolve a Schema and interact with a [PSR-7](https://www.php-fig.org/psr/psr-7/) compatible Response.

It can be easily integrated with an MVC application featuring Controllers like SLIM and Symfony, but is quite easy to adapt to any kind of application.

## Usages

* Inspect [App\Controller\GraphqlController](../app/src/Controller/GraphqlController.php)

>TODO: Improve this section of the documentation. ( :construction: WiP )

```php
$endpoint = new Endpoint();
$endpoint->executeSchema($SchemaOptions);
```

----
* Back to [README](../README.md)
