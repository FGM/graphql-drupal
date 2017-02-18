# Exposing new types

Queries are just new ways to ask for the same data, but usually extending the
GraphQL schema means defining fields with new data types. We shall create a new
module for this, called `graphql_example_type`,
which defines a way to obtain the latest `dblog` entries:

* create the module directory and `graphql_example_type.info.yml`, adding a
  dependency on `graphql`.

FIXME: continue
