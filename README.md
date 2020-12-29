# php.demo.api.graphql.slim
Just a DEMO API built around SLIM framework and GraphQL-PHP

## Work in progress


## Docker

From the project main directory:

(on windows, you may want to use `winpty` before `docker exec ...`)


```bash
docker-compose build
docker-compose run
docker-compose up -d
docker-compose down
```

Connect to container as CLI (optional)
```bash
docker exec -ti gql_slim_api sh
```


## GraphQL

### Endpoints

* `/graphql/blog` The 'blog' api endpoint and schema.
* `/graphql/refs` Another 'refs' api endpoint with another schema