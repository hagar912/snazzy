<?php

namespace Drupal\term_quick_edit\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\taxonomy\TermInterface;

/**
 * Defines HelloController class.
 */
class TermEditModeController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function editForm(TermInterface $taxonomy_term) {
    $form = \Drupal::service('entity.form_builder')->getForm($taxonomy_term, 'quick_edit');
    return $form;
  }

  /**
   * Checking permission to edit term.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Drupal\Core\Session\AccountInterface.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Drupal\Core\Access\AccessResult.
   */
  public function access(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'administer taxonomy');
  }

}
