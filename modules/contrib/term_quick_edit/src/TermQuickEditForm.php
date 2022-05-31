<?php

namespace Drupal\term_quick_edit;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\TermForm;

/**
 * Class TermQuickEditForm.
 *
 * @package Drupal\term_quick_edit
 */
class TermQuickEditForm extends TermForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['actions']['submit']['#ajax'] = [
      'callback' => '::submitTermEditForm',
      'wrapper' => 'ajax-form-modal-response',
    ];
    $prefix = '<div id="ajax-form-modal-response">';
    $form['#prefix'] = $form['#prefix'] ? $form['#prefix'] . $prefix : $prefix;
    $form['#suffix'] = '</div>';
    $form['actions']['cancel'] = [
      '#value' => $this->t('Cancel'),
      '#type' => 'submit',
      '#weight' => 9,
      '#ajax' => [
        'callback' => '::cancelModal',
      ],
    ];
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
   * @return \Drupal\Core\Ajax\AjaxResponse|array
   *   Drupal\Core\Ajax\AjaxResponse.
   */
  public function submitTermEditForm(array $form, FormStateInterface $form_state) {
    if (empty($form_state->getErrors())) {
      $response = new AjaxResponse();
      $persist = FALSE;
      $response->addCommand(new CloseModalDialogCommand($persist));

      $selector = '.ajax-quick-edit-title-' . $this->entity->id();
      $content = $this->entity->label();
      $response->addCommand(new InvokeCommand($selector, 'html', [$content]));
      return $response;
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

}
