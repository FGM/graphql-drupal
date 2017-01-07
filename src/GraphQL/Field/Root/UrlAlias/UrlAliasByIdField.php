<?php

namespace Drupal\graphql\GraphQL\Field\Root\UrlAlias;

use Drupal\Core\Config\Entity\ConfigEntityTypeInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Path\AliasStorageInterface;
use Drupal\graphql\GraphQL\Field\FieldBase;
use Drupal\graphql\Utility\StringHelper;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\IntType;
use Youshido\GraphQL\Type\Scalar\StringType;
use Youshido\GraphQL\Type\TypeInterface;

class UrlAliasByIdField extends FieldBase implements ContainerAwareInterface {
  use ContainerAwareTrait;

  /**
   * @var \Drupal\Core\Path\AliasStorageInterface
   */
  protected $aliasStorage;

  /**
   * Constructs an EntityByIdField object.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entityType
   *   The entity type handled by this field instance.
   * @param \Youshido\GraphQL\Type\TypeInterface $outputType
   *   The GraphQL type that this field resolves to.
   */
  public function __construct(AliasStorageInterface $aliasStorage, TypeInterface $outputType) {
    $this->aliasStorage = $aliasStorage;

    $config = [
      'name' => "UrlAliasById",
      'type' => $outputType,
      'args' => [
        'id' => new NonNullType(new IntType()),
      ],
    ];

    parent::__construct($config);
  }

  /**
   * Resolve function for this field.
   *
   * Loads an entity by its entity id.
   *
   * @param $value
   *   The parent value. Irrelevant in this case.
   * @param array $args
   *   The array of arguments. Contains the id of the entity to load.
   * @param \Youshido\GraphQL\Execution\ResolveInfo $info
   *   The context information for which to resolve.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The loaded entity object or NULL if there is no entity with the given id.
   */
  public function resolve($value, array $args = [], ResolveInfo $info) {
    $result = $this->aliasStorage->load(['pid' => $args['id']]);
    return $result;
  }
}
