<?php

namespace Drupal\term_quick_edit\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\taxonomy\TermInterface;

/**
 * Defines HelloController class.
 */
class TermQuickDeleteController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function deleteForm(TermInterface $taxonomy_term) {
    $form = \Drupal::service('entity.form_builder')->getForm($taxonomy_term, 'quick_delete');
    return $form;
  }

}
