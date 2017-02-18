<?php

namespace Drupal\graphql_example_type\GraphQL\Type;

use Drupal\graphql\GraphQL\Relay\Field\GlobalIdField;
use Drupal\graphql\GraphQL\Type\AbstractObjectType;

class DbLogEntryType extends AbstractObjectType {

  /**
   * {@inheritdoc}
   */
  public function build($config) {
    $config->addField(new GlobalIdField('dblog'));
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'DbLogEntry';
  }
}
