<?php

namespace Drupal\term_quick_edit;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Form\TermDeleteForm;
use Drupal\term_quick_edit\Ajax\PageReloadCommand;

/**
 * Class QuickDeleteForm.
 *
 * @package Drupal\term_quick_edit
 */
class TermQuickDeleteForm extends TermDeleteForm {

  protected $deleteId;
  protected $hasChild = FALSE;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['actions']['cancel'] = [
      '#value' => $this->t('Cancel'),
      '#type' => 'button',
      '#weight' => 9,
      '#ajax' => [
        'callback' => '::cancelModal',
      ],
    ];
    $form['actions']['submit']['#ajax'] = [
      'callback' => '::submitDeleteForm',
    ];
    $form['#attached']['library'][] = 'term_quick_edit/page-reload';
    $this->deleteId = $this->entity->id();
    /** @var \Drupal\taxonomy\TermStorage $term_storage */
    $term_storage = $this->entityTypeManager->getStorage('taxonomy_term');
    if (!empty($term_storage->loadChildren($this->entity->id()))) {
      $this->hasChild = TRUE;
    }
    return $form;
  }

  /**
   * Return ajax command.
   *
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Drupal\Core\Form\FormStateInterface.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Drupal\Core\Ajax\AjaxResponse.
   */
  public function cancelModal(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $persist = FALSE;
    $response->addCommand(new CloseModalDialogCommand($persist));
    return $response;
  }

  /**
   * Reload page after delete.
   *
   * @param array $form
   *   Form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Drupal\Core\Form\FormStateInterface.
   *
   * @return array|\Drupal\Core\Ajax\AjaxResponse
   *   Drupal\Core\Ajax\AjaxResponse.
   */
  public function submitDeleteForm(array $form, FormStateInterface $form_state) {

    if (!$form_state->getErrors()) {
      $response = new AjaxResponse();
      $persist = FALSE;
      $response->addCommand(new CloseModalDialogCommand($persist));
      if ($this->hasChild) {
        $response->addCommand(new PageReloadCommand());
      }
      else {
        $response->addCommand(new RemoveCommand('tr[data-drupal-selector="edit-terms-tid' . $this->deleteId . '0"]'));
      }
      return $response;

    }
    return $form;
  }

}
