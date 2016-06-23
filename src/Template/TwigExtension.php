<?php

namespace Drupal\tattoo\Template;

use Drupal\Core\Entity\Display\EntityViewDisplayInterface;
use Drupal\Core\Entity\Entity\EntityViewDisplay;
use Drupal\Core\Entity\EntityInterface;
use Drupal\tattoo\Parlor;

class TwigExtension extends \Twig_Extension {

  /**
   * @var \Drupal\tattoo\Parlor
   */
  private $parlor;

  public function __construct(Parlor $parlor) {

    $this->parlor = $parlor;
  }

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
    return ['#attached' => ['html_head' => [$this->parlor->toHead($entity)]]];
  }
}
