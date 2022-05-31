<?php

namespace Drupal\term_quick_edit\Ajax;

use Drupal\Core\Ajax\CommandInterface;

/**
 * Class PageReloadCommand.
 */
class PageReloadCommand implements CommandInterface {

  /**
   * Render custom ajax command.
   *
   * @return array
   *   Command options.
   */
  public function render() {
    return [
      'command' => 'pageReload',
    ];
  }

}
