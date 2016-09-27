<?php

namespace Drupal\graphql_entity_schema\Fields;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Entity\EntityTypeBundleInfo;
use Drupal\Core\Entity\EntityTypeManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

class EntityBundlesField extends AbstractField implements ContainerAwareInterface, RefinableCacheableDependencyInterface {

  use ContainerAwareTrait;
  use RefinableCacheableDependencyTrait;

  /**
   * {@inheritdoc}
   */
  public function build(FieldConfig $config) {
    parent::build($config);
    $config->addArgument('entityType', [
      'type' => new NonNullType(new StringType()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    $enumType = new ListType(new StringType);
    return $enumType;
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'entityBundles';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve($value, array $args, ResolveInfo $info) {
    $type = $args['entityType'];
    /** @var EntityTypeBundleInfo $entityTypeManager */
    $bundleInfo = $this->container->get('entity_type.bundle.info');
    $bundleNames = array_keys($bundleInfo->getBundleInfo($type));
    return $bundleNames;
  }
}
