# GraphQL


## Introduction

With smartphone dominance in today's world, companies wants to make their apps, their products, evolve and grow beyond their legacy scope, but they all are faced with an arch nemesis: A Major technical debt, years of legacy coding without anticipating their next need. A trend emmerged: splitting your legacy code into micro-service or multiple smaller services.

Legacy architectures often fails when faced with a renewed need for user growth. High user load, is a problem many will only encounter when evolving behond their initial, local, scope. Going Global, thinking World instead of just one place, one country, one type of people, will make your legacy stack crash burning to the ground with a torrent of tears from managers at political levels demanding what the hell happened and why they cannot generate more revenue despite having the 'perfect' product. Why is your app so costly to maintain and make evolve ?!

In that context, GraphQL arrived in 2012 thanks to Facebook's mobile needs and it spread like a virus. The pandemic is still running and many companies are considering using it to better face the challenge of flexibility required when communicating between many apps and domains in their evergrowing ecosystems.

Hopefully, you will discover here that GraphQL isn't just a query language, its should be viewed as a whole philosophy in itself, and its ideally suited for "Domain Driven" and "Backend For Front" architecture patterns. It can help you design interactions between your services, and also inside your services.


### A query language for your API

GraphQL is a query language for APIs and a runtime for fulfilling those queries with your existing data. GraphQL provides a complete and understandable description of the data in your API, gives clients the power to ask for exactly what they need and nothing more, makes it easier to evolve APIs over time, and enables powerful developer tools.



### Main advantages

* With each request you make, ask for what you need, get exactly that.
* With each request, get many resources, in a single request.
* Auto documentation & discovery: Describe what’s possible, with a Type system.
* Evolve your API without versions.
* Bring your own, data and code, from multiple sources.
* Built for Service-Oriented Architectures.
* Powerful developer tools & IDE.
* Multi language [implementations](https://graphql.org/code/) of the specifications (Node, PHP, ...).
* Huge developer community and literature.
* 8 Years of opensource maturity, used by [big names](https://landscape.graphql.org/category=graph-ql-adopter&format=card-mode&grouping=category): Facebook, Github, Pinterest, Airbnb, Atlassian, Rakuten, Twitter, New Relic, Paypal, ...


### Main disadvantages

* Queries (JSON payload) can become quite heavy in size and verbosity, but this is addressed by implementing [Automatic Persisted Queries](https://www.apollographql.com/docs/apollo-server/performance/apq/) (APQ) and Redis.
* A lot of Types in your schema can result in MANY naming conflicts, this require careful consideration when architecturing your schema, and some technical solutions can be implemented to alleviate the issue.

---
* [Read more](https://graphql.org/)


## Learning: Getting Started

### Integrated Development Environment (IDE)

To produce a quality product, you must use good tools. Thanks to a prolific community, you can find below some the most renowned IDEs for GraphQL, which are essentially glorified query editors and schema explorers.

To keep it simple and basic, I suggest starting with using one of many implementation of `GraphiQL`.

* [GraphiQL / Electron](https://www.electronjs.org/apps/graphiql)
* [GraphiQL chrome extension](https://chrome.google.com/webstore/detail/graphiql-extension/jhbedfdjpmemmbghfecnaeeiokonjclb?hl=en)
* [GraphiQL DevTools Firefox](https://addons.mozilla.org/en-US/firefox/addon/graphql-developer-tools/)
* [GraphiQL](https://github.com/graphql/graphiql)
* [Insomnia](https://insomnia.rest/graphql/)
* [GraphQL Playground](https://github.com/graphql/graphql-playground)
* [Apollo Studio](https://www.apollographql.com/blog/apollo-studio-a-graphql-ide-for-every-environment/)
* [Grahpql Editor](https://graphqleditor.com/)
* [Altair](https://altair.sirmuel.design/)



### RTFM - Read The Fracking Manual

When learning all the core concepts of GraphQL, [graphql.org](https://graphql.org/learn/) should become your new bible. Everything is explained, and some more advanced concepts can be found on [Apollo Docs](https://www.apollographql.com/docs/) a prolific company in the community and the goto resource when building graphql clients with javascript.


## Main Concepts

![GraphQL General Architecture](./imgs/gql-general-archi.png "GraphQL General Architecture")

### Query

A `Query` is a request operation made against a GraphQL server. It should be read-only, like a client asking your API server for some data.

Exemple of a query payload you can send to your graphql API Server Endpoint:
```gql
query {
    articles (offset: 0, limit: 2, locale: "en") {
        id,
        title,
        locale,
        tags (locale: "fr") {
            id,
            label,
            locale,
        },
    }
}
```

JSON response: 
```json
{
    "data": {
        "articles": [
            {
                "id": "24953191-42a3-4bd0-88c4-812a878cbfa9",
                "title": "How to travel the world in 2020",
                "locale": "en",
                "tags": [
                    {
                        "id": "qrV4z",
                        "title": "Voyager",
                        "locale": "fr",
                    },
                    {
                        "id": "tgaQ17",
                        "title": "Monde",
                        "locale": "fr",
                    },
                ],
            },
            {
                "id": "30b646ad-6229-488c-b9fa-a29127027360",
                "title": "Build a swiming pool at home",
                "locale": "en",
                "tags": [
                    {
                        "id": "oipG1h",
                        "label": "Piscines",
                        "locale": "fr",
                    },
                    {
                        "id": "zreT5d",
                        "label": "Extérieur maison",
                        "locale": "fr",
                    },
                ],
            }
        ]
    }
}
```




### Mutation

A `Mutation` is a request operation made against a GraphQL server. it should read-write datas, for exemple  used when you have to tell your backend to *mutate* some data.

Exemple of a mutation payload you can send to your API:
```gql
mutation {
   updateArticle (
     id: "14e43059-5d8e-47fc-a05e-6d8498d5ada2"
     locale: "en"
     input: {
        title: "Lorem Ipsum",
        tags: [
            "print",
            "sales",
        ]
        categories: [
        "R6vNW9BEo1",
        "R6vNW9l6Ew",
        ],
   })
   {
        id,
        isUpdated,
   }
}
```
As a result of the mutation, we will be getting two fields `id, title,` from the mutation's response type.

JSON response: 
```json
{
    "data": {
        "updateArticle": {
            "id": "14e43059-5d8e-47fc-a05e-6d8498d5ada2",
            "isUpdated": true,
        },
    }
}
```


### Type

A `Type` is a 'data' definition. It is used to define what data is available in what other data.
Every input/output of your API will be a defined type. Types can also be extended and be created programatically. Those can be seen as your API's models. You will have to write some code later, known as *Resolver*, to make sure you can push some data for your Type fields.

Exemple of some type definitions
```gql
type ArticleTag {
    id: ID!,
    locale: String!,
    label: String!,
},

type Article {
    id: ID!,
    title: String!,
    """
    A simplified localization identifier to know in which language the article is writen.
    """
    locale: String!,
    tags: [ArticleTag!]!
}

type Query {
    """
    Get a list of articles
    """
    articles (offset: Int! = 0, limit: Int! = 10,) : Article
}

type UpdateArticleMutationResult {
    id: ID!,
    isUpdated: Boolean
}
type Mutation {
    updateArticle (offset: Int! = 0, limit: Int! = 10,) : UpdateArticleMutationResult
}

```
* `!` means '*non-nullable*'
* `[` and `]` means '*array of*'
* A text between `"""` lines is a comment, often displayed in auto-documentation, and akin to PHP Annotations and PHPDoc.
* '*Mutation.updateArticle*' accept `Input` types as `Arguments`, and must return an '*Article*' type,
* A '[*Scalar*](https://graphql.org/learn/schema/#scalar-types)' is a primitive Type, such as a `String`, `Int`, `Boolean`, `Float`, etc.


As you can see, your Queries and Mutations are ALSO defined Types accepting input and returning output, very much like a *function* in a programming language. Well, the executable form of that function will be called a *Resolver* later.

> Many opensource GraphQL wrappers, like API Platform, tends to auto-generate types based on ORM Models. I find it a cheap approach without much interest. We want our API to expose QUALIFIED Types that do and describe your business intuitively, and fulfil your actual technical needs, not just every ugly table and field you have on your SQL server, exposing your ugly naming conventions, if you were lucky to have one... We also want to tailor craft resolvers when we need to.

### Schema

A `Schema` is the name we give to all the Types defined and available for a given Endpoint.
The previous *Type* exemple is a part of a Schema.

This schema can be requested by a client, thus making programatically possible to have auto-discovery of types and documentation, heavily used in your typical GraphQL IDE.

This Schema is what we can give to our graphql implementation, like graphql-php, to make it work.
In the real world, and in this demo project, this schema will be stitched from multiple files and can even be created directly in a specific language structure, like PHP or Javascript Objects.

> A well described and commented schema is a schema without further need of documentation, exit Swagger !

Read more:
* [Schema Definition in graphql-php](https://webonyx.github.io/graphql-php/type-system/schema/)
* [How to write comments in a GraphQL Schema to enhance auto-generated documentation](https://medium.com/@krishnaregmi/how-to-write-comments-in-a-graphql-schema-to-enhance-auto-generated-documentation-c0047125ea24)


### Resolver

A `Resolver` is an algorithm you code to "resolve" the data requested in Queries and Mutations. You can also write resolvers for every Type in your Schema, although most graphql implementations will have automatic resolvers for such, if the behavior you need is [standard enough](https://graphql.org/learn/execution/#trivial-resolvers) like mapping an object property with your GQL Type field of the same name.


---
> TODO: polishing the documentation

 



---
Learn everything in more details at graphql.org ! It only takes a very few hours and is more impressive on the listing than in reality.

## Queries and Mutations

* [Read here](https://graphql.org/learn/queries)
* [Fields](https://graphql.org/learn/queries/#fields)
* [Arguments](https://graphql.org/learn/queries/#arguments)
* [Aliases](https://graphql.org/learn/queries/#aliases)
* [Fragments](https://graphql.org/learn/queries/#fragments)
* [Operation Name](https://graphql.org/learn/queries/#operation-name)
* [Variables](https://graphql.org/learn/queries/#variables)
* [Directives](https://graphql.org/learn/queries/#directives)
* [Mutations](https://graphql.org/learn/queries/#mutations)
* [Inline Fragments](https://graphql.org/learn/queries/#inline-fragments)


## Schemas and Types

* [Read here](https://graphql.org/learn/schema)
* [Type System](https://graphql.org/learn/schema/#type-system)
* [Type Languague](https://graphql.org/learn/schema/#type-system)
* [Object Types and Fields](https://graphql.org/learn/schema/#object-types-and-fields)
* [Arguments](https://graphql.org/learn/schema/#arguments)
* [The Query and Mutation Types](https://graphql.org/learn/schema/#the-query-and-mutation-types)
* [Scalar Types](https://graphql.org/learn/schema/#scalar-types)
* [Enumeration Types](https://graphql.org/learn/schema/#enumeration-types)
* [Lists and Non-Null](https://graphql.org/learn/schema/#lists-and-non-null)
* [Interfaces](https://graphql.org/learn/schema/#interfaces)
* [Union Types](https://graphql.org/learn/schema/#union-types)
* [Input Types](https://graphql.org/learn/schema/#input-types)


## Validation

* [Read here](https://graphql.org/learn/validation)


## Execution

* [Read here](https://graphql.org/learn/execution)
* [Root fields & resolvers](https://graphql.org/learn/execution/#root-fields-resolvers)
* [Asynchronous resolvers](https://graphql.org/learn/execution/#asynchronous-resolvers)
* [Trivial resolvers](https://graphql.org/learn/execution/#trivial-resolvers)
* [Scalar coercion](https://graphql.org/learn/execution/#scalar-coercion)
* [List resolvers](https://graphql.org/learn/execution/#list-resolvers)
* [Producing the result](https://graphql.org/learn/execution/#producing-the-result)

## Introspection

* [Read here](https://graphql.org/learn/introspection)

----

* Back to [README](../README.md)
