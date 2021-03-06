/**
 * @file
 * Testing behaviors for tabledrag library.
 */
(function($, Drupal) {
  /**
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Removes a test class from the handle elements to allow verifying that
   *   dragging operations have been executed.
   */
  Drupal.behaviors.tableDragTest = {
    attach(context) {
      $('.tabledrag-handle', context)
        .once('tabledrag-test')
        .on('keydown.tabledrag-test', event => {
          $(event.currentTarget).removeClass('tabledrag-test-dragging');
        });
    },
  };
})(jQuery, Drupal);
