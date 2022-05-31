<?php

namespace Drupal\term_quick_edit\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleHandler;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\taxonomy\Entity\Vocabulary;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Referenced Content Moderation Settings.
 */
class TermQuickEditSettings extends ConfigFormBase {

  /**
   * Drupal\Core\Extension\ModuleHandler definition.
   *
   * @var \Drupal\Core\Extension\ModuleHandler
   */
  protected $moduleHandler;

  /**
   * Drupal\Core\Entity\EntityTypeManagerInterface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a AddToAnySettingsForm object.
   *
   * @param \Drupal\Core\Extension\ModuleHandler $module_handler
   *   The factory for configuration objects.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   Drupal\Core\Config\ConfigFactoryInterface.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   */
  public function __construct(ModuleHandler $module_handler, ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->moduleHandler = $module_handler;
    parent::__construct($config_factory);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('module_handler'),
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Provides Configuration Form name.
   */
  public function getFormId() {
    return 'term_quick_edit_settings';
  }

  /**
   * Provides Configuration Page name for Accessing the values.
   */
  protected function getEditableConfigNames() {
    return [
      "term_quick_edit.settings",
    ];
  }

  /**
   * Creates a Form for Configuring the Module.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config("term_quick_edit.settings");
    $vocabularies = $this->getVocabularies();
    $form['vocabulary'] = [
      '#type' => 'details',
      '#title' => 'Enable Term Quick Edit for vocabulary',
      '#open' => TRUE,
    ];
    foreach ($vocabularies as $vocabulary) {
      $form['vocabulary'][$vocabulary->id()] = [
        '#type' => 'checkbox',
        '#title' => $vocabulary->label(),
        '#default_value' => $config->get('taxonomy.' . $vocabulary->id()),
      ];
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * Submits the Configuration Form.
   *
   * @param array $form
   *   Form array.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Drupal\Core\Form\FormStateInterface.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $config = $this->config('term_quick_edit.settings');
    $vocabularies = $this->getVocabularies();
    foreach ($vocabularies as $vocabulary) {
      $config->set('taxonomy.' . $vocabulary->id(), $values[$vocabulary->id()]);
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

  /**
   * Get vocabulary.
   *
   * @return \Drupal\Core\Entity\EntityInterface[]
   *   Drupal\Core\Entity\EntityInterface[].
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function getVocabularies() {
    $storage = $this->entityTypeManager->getStorage('taxonomy_vocabulary');
    return $storage->loadByProperties();
  }

}
