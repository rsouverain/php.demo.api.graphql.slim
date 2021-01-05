# php.demo.api.graphql.slim

This project is a demo project aimed to demonstrate a slightly more advanced implementation of GraphQL in PHP than your average demo tutorial, yet still easy to understand.

## :star: Featuring

* Multi endpoints, each with a different graphql schema, or a composite schema sharing types between them.
* [GraphQL Automatic Persisted Queries](https://www.apollographql.com/docs/apollo-server/performance/apq/), a caching mechanism for queries, using Redis 4.
* (wip) GraphQL Schema Caching, using PHP OPCache.
* (wip) Dynamic graphql Types.

## :construction: Work in progress

This is a work in progress still in its early stage of infancy. Ultimately, the purpose of this project is educational and hopefully will evolve to adhere to the most relevant best practices available.

I've got a NodeJS implementation already in the pipe, buty will come out later since it was a practical case and not oriented toward an educational purpose.

## Starring libraries

* PHP 7.3
* [SLIM Framework](https://www.slimframework.com/)
* [Symfony Cache](https://symfony.com/doc/current/components/cache.html) ([PSR-6](https://symfony.com/doc/current/components/cache.html#basic-usage-psr-6), [Redis](https://symfony.com/doc/current/components/cache/adapters/redis_adapter.html))
* [GraphQL-PHP](https://webonyx.github.io/graphql-php)

## Documentations

### Transverse

* [Coding Standards](./docs/CodingStandards.md) : Read more about today's more favored Coding style.
* [GraphQL](./docs/GraphQL.md) : General purpose doc about GQL.

### Custom libraries

* [CacheManager](./docs/CacheManager.md) : For every intensive memoization needs, via Redis persistent connection, or other PSR-6 adapter.
* [OpcacheManager](./docs/OpcacheManager.md) : For file system caching needs, via OPcache.
* GraphQL [Endpoint](./docs/Endpoint.md) : Allows for quick endpoint creation using various schema composition.
* GraphQL [SchemaLoader](./docs/SchemaLoader.md) : Automatically find and load php files related to graphql type definition into a coherent GraphQL Schema.
* GraphQL APQ: @TODO
* Auth: @TODO

## GraphQL

### Endpoints

* `http://127.0.0.1:9632`
  * `/graphql/blog` The 'blog' api endpoint and schema.
  * `/graphql/refs` Another 'refs' api endpoint with another schema


## Docker

From the project main directory:


```bash
docker-compose build
docker-compose up -d
docker-compose down
```

Connect to container as CLI (optional)

```bash
# on windows, you may want to consider using the `winpty` command before `docker exec`)
docker exec -ti gql_slim_api sh
```
