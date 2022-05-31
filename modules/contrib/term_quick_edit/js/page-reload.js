/**
 * @file
 * Reload page.
 */

(function ($, Drupal) {
  /**
   * Add new custom command.
   */
  Drupal.AjaxCommands.prototype.pageReload = function (ajax, response, status) {
    location.reload();
  }
})(jQuery, Drupal);
