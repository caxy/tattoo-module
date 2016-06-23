<?php

namespace Drupal\tattoo;

use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\Serializer\Serializer;

class Parlor {
  /**
   * @var \Symfony\Component\Serializer\Serializer
   */
  private $serializer;

  public function __construct(Serializer $serializer) {
    $this->serializer = $serializer;
  }

  public function toHalJson(EntityInterface $entity) {
    // Normalize the entity so we can add more URLs to the _links array.
    $data = $this->serializer->normalize($entity, 'hal_json');

    // Save the unaliased URL as the shortlink.
    $url = $entity->toUrl('canonical', ['absolute' => TRUE, 'alias' => TRUE]);
    $url->setRouteParameter('_format', 'hal_json');
    $data['_links']['shortlink'] = ['href' => $url->toString()];

    return $this->serializer->encode($data, 'hal_json');
  }

  public function toHead(EntityInterface $entity) {
    return [
      [
        '#type' => 'html_tag',
        '#tag' => 'script',
        '#attributes' => [
          'type' => 'application/hal+json',
          'data-drupal-selector' => 'tattoo-entity',
          'data-tattoo-entity' => $entity->getEntityTypeId() . ':' . $entity->id(),
        ],
        '#value' => $this->toHalJson($entity),
      ],
      'tattoo_' . $entity->getEntityTypeId() . '_' . $entity->id(),
    ];
  }
}