<?php

namespace Drupal\graphql_entity_schema\Fields;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\graphql_entity_schema\Types\EntityTypeType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\GraphQL\Config\Field\FieldConfig;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Field\AbstractField;
use Youshido\GraphQL\Type\NonNullType;
use Youshido\GraphQL\Type\Scalar\StringType;

class EntityTypeField extends AbstractField implements ContainerAwareInterface {
  const ARG = 'entityType';

  use ContainerAwareTrait;

  /**
   * {@inheritdoc}
   */
  public function build(FieldConfig $config) {
    parent::build($config);
    $config->addArgument(static::ARG, [
      'type' => new NonNullType(new StringType()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return new EntityTypeType();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return static::ARG;
  }

  /**
   * {@inheritdoc}
   */
  public function resolve($value, array $args, ResolveInfo $info) {
    $type = $args[static::ARG];

    /** @var EntityTypeManagerInterface $entityTypeManager */
    $entityTypeManager = $this->container->get('entity_type.manager');

    $definition = $entityTypeManager->getDefinition($type);
    return $definition;
  }

}
