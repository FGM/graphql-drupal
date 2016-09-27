<?php

namespace Drupal\graphql_entity_schema;

use Drupal\graphql_entity_schema\Fields\EntityBundlesField;
use Drupal\graphql_entity_schema\Fields\EntityTypesField;
use Drupal\graphql\SchemaProviderInterface;

class SchemaProvider implements SchemaProviderInterface {
  /**
   * {@inheritdoc}
   */
  public function getQuerySchema() {
    return [
      new EntityBundlesField(),
      new EntityTypesField(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getMutationSchema() {
    return [];
  }
}
