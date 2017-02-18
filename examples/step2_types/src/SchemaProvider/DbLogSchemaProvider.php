<?php

namespace Drupal\graphql_example_type\SchemaProvider;

use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\graphql\SchemaProvider\SchemaProviderBase;
use Drupal\graphql\TypeResolver\TypeResolverInterface;
use Drupal\graphql_example_query\GraphQL\Field\Root\LatestUserField;
use Drupal\graphql_example_type\GraphQL\Field\Root\DbLogEntryField;

/**
 * Class DbLogSchemaProvider exposes a "log entry" field.
 */
class DbLogSchemaProvider extends SchemaProviderBase {

  /**
   * The type resolver service.
   *
   * @var \Drupal\graphql\TypeResolver\TypeResolverInterface
   */
  protected $typeResolver;

  /**
   * The typed data manager service.
   *
   * @var \Drupal\Core\TypedData\TypedDataManager
   */
  protected $typedDataManager;

  /**
   * LatestUserSchemaProvider constructor.
   *
   * @param \Drupal\Core\TypedData\TypedDataManagerInterface $typedDataManager
   *   The typed_data_manager service.
   * @param \Drupal\graphql\TypeResolver\TypeResolverInterface $typeResolver
   *   The graphql.type_resolver service.
   */
  public function __construct(TypedDataManagerInterface $typedDataManager, TypeResolverInterface $typeResolver) {
    $this->typedDataManager = $typedDataManager;
    $this->typeResolver = $typeResolver;
  }

  /**
   * {@inheritdoc}
   */
  public function getQuerySchema() {
    $fields = [
      new DbLogEntryField(),
    ];
    return $fields;
  }

}
