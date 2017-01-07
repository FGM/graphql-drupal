<?php

namespace Drupal\graphql\SchemaProvider;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Path\AliasStorageInterface;
use Drupal\Core\TypedData\TypedDataManager;
use Drupal\graphql\GraphQL\Field\Root\Entity\EntityByIdField;
use Drupal\graphql\GraphQL\Field\Root\Entity\EntityByPathField;
use Drupal\graphql\GraphQL\Field\Root\Entity\EntityByUuidField;
use Drupal\graphql\GraphQL\Field\Root\Entity\EntityQueryField;
use Drupal\graphql\GraphQL\Field\Root\UrlAlias\UrlAliasByIdField;
use Drupal\graphql\TypeResolver\TypeResolverInterface;

/**
 * Generates a GraphQL Schema for url aliases.
 */
class UrlAliasSchemaProvider extends SchemaProviderBase {
  /**
   * The path.alias_storage service.
   *
   * @var \Drupal\Core\Path\AliasStorageInterface
   */
  protected $aliasStorage;

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
   * Constructs a EntitySchemaProvider object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   *   The entity manager service.
   * @param \Drupal\Core\TypedData\TypedDataManager $typedDataManager
   *   The typed data manager service.
   * @param \Drupal\graphql\TypeResolver\TypeResolverInterface $typeResolver
   *   The type resolver service.
   */
  public function __construct(AliasStorageInterface $aliasStorage, TypedDataManager $typedDataManager, TypeResolverInterface $typeResolver) {
    $this->aliasStorage = $aliasStorage;
    $this->typeResolver = $typeResolver;
    $this->typedDataManager = $typedDataManager;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    // FIXME
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getQuerySchema() {
    // FIXME
    return [];
    $dataDefinition = $this->typedDataManager->createDataDefinition("any");
    $urlAliasType = $this->typeResolver->resolveRecursive($dataDefinition);
    $field = new UrlAliasByIdField($this->aliasStorage, $urlAliasType);
    return [$field];

    $entityTypes = $this->entityTypeManager->getDefinitions();
    $entityTypeTypes = array_map(function (EntityTypeInterface $entityType) {
      $entityTypeId = $entityType->id();
      $dataDefinition = $this->typedDataManager->createDataDefinition("entity:$entityTypeId");
      return $this->typeResolver->resolveRecursive($dataDefinition);
    }, $entityTypes);

    $entityTypeKeys = array_keys($entityTypes);
    $fields = array_reduce($entityTypeKeys, function ($carry, $key) use ($entityTypes, $entityTypeTypes) {
      /** @var \Drupal\Core\Entity\EntityTypeInterface $entityType */
      $entityType = $entityTypes[$key];
      $entityTypeType = $entityTypeTypes[$key];

      if ($entityType->hasKey('uuid')) {
        array_push($carry, new EntityByUuidField($entityTypes[$key], $entityTypeTypes[$key]));
      }

      array_push($carry, new EntityByIdField($entityType, $entityTypeType));
      array_push($carry, new EntityQueryField($entityType, $entityTypeType, $this->typedDataManager, $this->typeResolver));

      return $carry;
    }, []);

    return array_merge($fields, [
      new EntityByPathField(),
    ]);
  }
}
