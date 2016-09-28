<?php

namespace Drupal\graphql_entity_schema\Fields;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\graphql_entity_schema\Types\EntityTypeType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;

class EntityTypesField extends AbstractField implements ContainerAwareInterface, RefinableCacheableDependencyInterface {

  use ContainerAwareTrait;
  use RefinableCacheableDependencyTrait;

  /**
   * {@inheritdoc}
   */
  public function build(FieldConfig $config) {
    parent::build($config);
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return new ListType(new EntityTypeType());
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'entityTypes';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve($value, array $args, ResolveInfo $info) {
    /** @var EntityTypeManager $entityTypeManager */
    $entityTypeManager = $this->container->get('entity_type.manager');

    $typeDefinitions = $entityTypeManager->getDefinitions();
    sort($typeDefinitions);
    return $typeDefinitions;
  }
}
