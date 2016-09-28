<?php

namespace Drupal\graphql_entity_schema\Types;

use Drupal\Component\Utility\Unicode;
use Drupal\Core\Entity\EntityTypeInterface;
use Youshido\GraphQL\Execution\ResolveInfo;
use Youshido\GraphQL\Type\ListType\ListType;
use Youshido\GraphQL\Type\Object\AbstractObjectType;
use Youshido\GraphQL\Type\Scalar\StringType;

class EntityTypeType extends AbstractObjectType  {
  public function build($config) {
    $config->addField('id', [
      'type' => new StringType(),
      'resolve' => [__CLASS__, 'resolveSimpleProperty'],
    ]);
    $config->addField('provider', [
      'type' => new StringType(),
      'resolve' => [__CLASS__, 'resolveSimpleProperty'],
      'description' => 'The entity type provider',
    ]);
    $config->addField('keys', [
      'type' => new ListType(new StringType()),
      'resolve' => [__CLASS__, 'resolvePropertyAsStringList'],
      'description' => 'The entity type keys',
    ]);
  }

  public function getDescription() {
    return "A simplified version of a Drupal EntityType";
  }

  /**
   * Resolve a value mapping to the GraphQL type
   *
   * @param EntityTypeInterface $parent
   * @param array $args
   * @param \Youshido\GraphQL\Execution\ResolveInfo $info
   *
   * @return mixed
   */
  public static function resolveSimpleProperty($parent, $args, ResolveInfo $info) {
    $name = $info->getField()->getName();
    if (method_exists($parent, $name)) {
      $callable = $name;
    }
    else {
      $method = 'get' . Unicode::ucwords($name);
      if (method_exists($parent, $method)) {
        $callable = $method;
      }
      else {
        $callable = NULL;
      }
    }
    $value = $callable ? $parent->{$callable}() : NULL;
    return $value;
  }

  /**
   * Resolve a value mapping to a hash of stringable scalars.
   *
   * This has no GraphQL representation.
   *
   * @param EntityTypeInterface $parent
   * @param array $args
   * @param \Youshido\GraphQL\Execution\ResolveInfo $info
   *
   * @return array<string,string>
   */
  public static function resolvePropertyAsStringList($parent, $args, ResolveInfo $info) {
    $name = $info->getField()->getName();
    $method = 'get' . Unicode::ucwords($name);
    $hash = method_exists($parent, $method) ? $parent->{$method}() : NULL;
    array_walk($hash, function (&$value, $key) { $value = "$key:$value"; });
    return $hash  ;
  }

}
