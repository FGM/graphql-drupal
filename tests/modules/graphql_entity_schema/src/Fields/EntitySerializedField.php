<?php

namespace Drupal\graphql_entity_schema\Fields;

use Drupal\Core\Cache\RefinableCacheableDependencyInterface;
use Drupal\Core\Cache\RefinableCacheableDependencyTrait;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Serializer\Serializer;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

class EntitySerializedField extends AbstractField implements ContainerAwareInterface, RefinableCacheableDependencyInterface {

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
    $config->addArgument('entityId', [
      'type' => new NonNullType(new StringType()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return new StringType();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'entitySerialized';
  }

  /**
   * {@inheritdoc}
   */
  public function resolve($value, array $args, ResolveInfo $info) {
    $type = $args['entityType'];
    $id = $args['entityId'];
    /** @var EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = $this->container->get('entity_type.manager');
    $storage = $entityTypeManager->getStorage($type);
    $entity = $storage->load($id);
    /** @var Serializer $serializer */
    $serializer = $this->container->get('serializer');
    $json = $serializer->serialize($entity, 'json');
    return $json;
  }

}
