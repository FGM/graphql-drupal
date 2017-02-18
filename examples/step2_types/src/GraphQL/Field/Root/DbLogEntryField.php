<?php

namespace Drupal\graphql_example_type\GraphQL\Field\Root;

use Drupal\graphql\GraphQL\Field\FieldBase;
use Drupal\graphql_example_type\GraphQL\Field\SelfAwareField;
use Drupal\graphql_example_type\GraphQL\Type\DbLogEntryType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\AbstractType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\TypeInterface;

/**
 * Class DbLogEntryField defines a "dbLogEntry" field.
 */
class DbLogEntryField extends SelfAwareField implements ContainerAwareInterface {
  use ContainerAwareTrait;

  /**
   * @return AbstractObjectType|AbstractType
   */
  public function getType() {
    return new DbLogEntryType();
  }

  /**
   * Resolve function for this field.
   *
   * Loads an entity by its entity id.
   *
   * @param mixed $value
   *   The parent value. Irrelevant in this case.
   * @param array $args
   *   The array of arguments. Contains the id of the entity to load.
   * @param \Youshido\GraphQL\Execution\ResolveInfo $info
   *   The context information for which to resolve.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The loaded entity object or NULL if there is no entity with the given id.
   */
  public function resolve($value, array $args, ResolveInfo $info) {
    /** @var \Drupal\Core\Entity\EntityTypeManager $entityTypeManager */
    $entityTypeManager = $this->container->get('entity_type.manager');
    $entityStorage = $entityTypeManager->getStorage('user');
    $query = $this->container->get('entity.query');
    $uids = $query
      ->getAggregate('user')
      ->aggregate('uid', 'MAX')
      ->execute();
    $uid = reset($uids);
    return $entityStorage->load($uid['uid_max']);
  }

}
