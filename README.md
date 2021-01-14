# php.demo.api.graphql.slim

This project is a demo project aimed to demonstrate a slightly more advanced implementation of GraphQL in PHP than your average demo tutorial, yet still as bare and easy to understand as possible.

## :star: Featuring

* Multi HTTP endpoints, each with a different graphql schema, or a composite schema sharing types between them.
* [GraphQL Automatic Persisted Queries](https://www.apollographql.com/docs/apollo-server/performance/apq/), server implementation of a very popular and efficient caching mechanism for queries, using Redis as persistent storage.
* (wip) GraphQL Schema caching, using PHP OPCache.
* (wip) Dynamic GraphQL Types.
* (wip) DataLoader, to prevent duplicate data fetchs.
* (wip) Data Repositories, to centralize fetching data from your backends, because graphql is all about data aggregation and composition from various sources.

With all those features, you have a good variety of efficient tools to bootstrap and host a real world GraphQL API, scalable and capable of sustaining some decent loads.
Moreover, you can re-use the various cache mechanisms to suit your own development needs.

### A word about cache
GraphQL resolver workflow makes it (very) bad, performance wise, when it comes to fetching a lot of data from one single request.
PHP makes this even worse if not using asynchronous promises or some other tools.
 
Because of this GraphQL's inherent Achilles' heel, you can see a good portion of those features mention one or another cache mechanism.
Those are required in a real world situation where we must ensure as little response time as possible.  

I like to think about cache as if it was Achilles' shield, protecting him from taking an arrow in the first place.
Every cache have its own hiccups, but for the purpose of this demo this is more than enough, and in the real, you can work them out as you encounter them.


## :construction: Work in progress

This is a work in progress still in its early stage of infancy. Ultimately, the purpose of this project is educational and hopefully will evolve to adhere to the most relevant best practices available.

I've got a NodeJS implementation already in the pipe, but it will come out later since it was a practical case and not oriented toward an educational purpose.

Also thinking about making a Symfony based version of this demo to demonstrate how to best leverage this framework in a GraphQL context.

> The demo is not "demonstrable" yet

## Documentations

### Starring libraries

* PHP 7.3
* [SLIM Framework](https://www.slimframework.com/) : A lightweight PHP framework, only used here to handle basic Routing. 
* [GraphQL-PHP](https://webonyx.github.io/graphql-php) : The PHP implementation of the core GraphQL standard specification.
* [DataLoader-PHP](https://github.com/overblog/dataloader-php) : An "in memory" cache for all data loads (by IDs) which occur within a single request to your application. Helpful (if not mandatory) with graphql to prevent duplicating data fetchs.
* [Symfony Cache](https://symfony.com/doc/current/components/cache.html) ([PSR-6](https://symfony.com/doc/current/components/cache.html#basic-usage-psr-6), [Redis](https://symfony.com/doc/current/components/cache/adapters/redis_adapter.html)) A standardized, persistent, key/value pair cache system.


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

## Consuming our GraphQL API

### Endpoints

* `http://127.0.0.1:9632`
  * `/graphql/blog` The 'blog' api endpoint and schema.
  * `/graphql/refs` Another 'refs' api endpoint with another schema
 
 
### User credentials

For demonstration purpose, some Types will not grant you access to some Fields depending on which user you are.
 
* With elevated privileges: `admin`/`admin`
* With normal end-user permissions: `member`/`member`


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

Don't forgot to create `.env` file
