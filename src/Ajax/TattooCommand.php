<?php

namespace Drupal\tattoo\Ajax;

use Drupal\Core\Ajax\CommandInterface;
use Drupal\Core\Entity\EntityInterface;

class TattooCommand implements CommandInterface
{
  /**
   * @var EntityInterface
   */
  private $entity;

  /**
   * @var bool
   */
  private $delete;

  public function __construct(EntityInterface $entity, $delete = FALSE)
  {
    $this->entity = $entity;
    $this->delete = $delete;
  }

  public function render() {
    return array(
      'command' => 'tattoo',
      'entity' => \Drupal::service('serializer')->normalize($this->entity, 'hal_json'),
      'delete' => $this->delete,
    );
  }
}
