/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal) {
  Drupal.behaviors.contentTypes = {
    attach: function attach(context) {
      var $context = $(context);

      $context.find('#edit-submission').drupalSetSummary(function (context) {
        var vals = [];
        vals.push(Drupal.checkPlain($(context).find('#edit-title-label').val()) || Drupal.t('Requires a title'));
        return vals.join(', ');
      });
      $context.find('#edit-workflow').drupalSetSummary(function (context) {
        var vals = [];
        $(context).find('input[name^="options"]:checked').next('label').each(function () {
          vals.push(Drupal.checkPlain($(this).text()));
        });
        if (!$(context).find('#edit-options-status').is(':checked')) {
          vals.unshift(Drupal.t('Not published'));
        }
        return vals.join(', ');
      });
      $('#edit-language', context).drupalSetSummary(function (context) {
        var vals = [];

        vals.push($('.js-form-item-language-configuration-langcode select option:selected', context).text());

        $('input:checked', context).next('label').each(function () {
          vals.push(Drupal.checkPlain($(this).text()));
        });

        return vals.join(', ');
      });
      $context.find('#edit-display').drupalSetSummary(function (context) {
        var vals = [];
        var $editContext = $(context);
        $editContext.find('input:checked').next('label').each(function () {
          vals.push(Drupal.checkPlain($(this).text()));
        });
        if (!$editContext.find('#edit-display-submitted').is(':checked')) {
          vals.unshift(Drupal.t("Don't display post information"));
        }
        return vals.join(', ');
      });
    }
  };
})(jQuery, Drupal);