/**
 * @file
 * Share button.
 */

(function ($, Drupal) {
  /**
   * Add new custom command.
   */
  Drupal.AjaxCommands.prototype.reloadPage = function (ajax, response, status) {
    console.log(response.message);
  }
})(jQuery, Drupal);

