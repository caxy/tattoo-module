<?php

namespace Drupal\tattoo\Template;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityInterface;

class TwigExtension extends \Twig_Extension {

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName() {
    return 'tattoo';
  }

  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('tattoo', array($this, 'tattoo')),
    ];
  }

  public function tattoo(EntityInterface $entity) {
    $build = [];
    $view_mode = 'default';
    /** @var EntityViewDisplayInterface $display */
    $display = EntityViewDisplay::load($entity->getEntityTypeId().'.'.$entity->bundle().'.'.$view_mode);
    tattoo_entity_view($build, $entity, $display, $view_mode);
    return $build;
  }
}