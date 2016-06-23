<?php

namespace Drupal\tattoo\Form;

use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Render\Element;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeRepository;

/**
 * Class SettingsForm.
 *
 * @package Drupal\tattoo\Form
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Drupal\Core\Entity\EntityTypeRepository definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeRepository
   */
  protected $entityTypeRepository;

  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeRepository $entity_type_repository) {
    parent::__construct($config_factory);
    $this->entityTypeRepository = $entity_type_repository;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.repository')
    );
  }


  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['tattoo.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'tattoo_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('tattoo.settings');
    $definitions = \Drupal::entityTypeManager()->getDefinitions();
    $definitions = array_filter($definitions, function (EntityTypeInterface $entityType) {
      return $entityType->getGroup() === 'content';
    });
    /** @var EntityTypeBundleInfoInterface $bundleInfo */
    $bundleInfo = \Drupal::service('entity_type.bundle.info');
    $form['entity_type'] = array_map(function (EntityTypeInterface $entityType) use ($config, $bundleInfo) {
      $element = [
        '#type' => 'checkboxes',
        '#title' => $entityType->getLabel(),
        '#default_value' => $config->get('entity_type.'.$entityType->id()) ?: [],
        '#options' => array_map(function ($info) {
          return $info['label'];
        }, $bundleInfo->getBundleInfo($entityType->id())),
      ];
      return $element;
    }, $definitions);
    $form['entity_type']['#tree'] = TRUE;

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $values = array_filter(array_map('array_values', array_map('array_filter', $form_state->getValue('entity_type'))));

    $this->config('tattoo.settings')
      ->set('entity_type', $values)
      ->save();
  }
}
