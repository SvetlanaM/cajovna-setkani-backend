/**
 * @file
 * Defines Javascript behaviors for the media form.
 */

(function($, Drupal) {
  /**
   * Behaviors for summaries for tabs in the media edit form.
   *
   * @type {Drupal~behavior}
   *
   * @prop {Drupal~behaviorAttach} attach
   *   Attaches summary behavior for tabs in the media edit form.
   */
  Drupal.behaviors.mediaFormSummaries = {
    attach(context) {
      const $context = $(context);

      $context.find('.media-form-author').drupalSetSummary(context => {
        const $authorContext = $(context);
        const name = $authorContext.find('.field--name-uid input').val();
        const date = $authorContext.find('.field--name-created input').val();

        if (name && date) {
          return Drupal.t('By @name on @date', {
            '@name': name,
            '@date': date,
          });
        }
        if (name) {
          return Drupal.t('By @name', { '@name': name });
        }
        if (date) {
          return Drupal.t('Authored on @date', { '@date': date });
        }
      });
    },
  };
})(jQuery, Drupal);
