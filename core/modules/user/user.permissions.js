/**
* DO NOT EDIT THIS FILE.
* See the following change record for more information,
* https://www.drupal.org/node/2815083
* @preserve
**/

(function ($, Drupal) {
  Drupal.behaviors.permissions = {
    attach: function attach(context) {
      var self = this;
      $('table#permissions').once('permissions').each(function () {
        var $table = $(this);
        var $ancestor = void 0;
        var method = void 0;
        if ($table.prev().length) {
          $ancestor = $table.prev();
          method = 'after';
        } else {
          $ancestor = $table.parent();
          method = 'append';
        }
        $table.detach();

        var $dummy = $('<input type="checkbox" class="dummy-checkbox js-dummy-checkbox" disabled="disabled" checked="checked" />').attr('title', Drupal.t('This permission is inherited from the authenticated user role.')).hide();

        $table.find('input[type="checkbox"]').not('.js-rid-anonymous, .js-rid-authenticated').addClass('real-checkbox js-real-checkbox').after($dummy);

        $table.find('input[type=checkbox].js-rid-authenticated').on('click.permissions', self.toggle).each(self.toggle);

        $ancestor[method]($table);
      });
    },
    toggle: function toggle() {
      var authCheckbox = this;
      var $row = $(this).closest('tr');

      $row.find('.js-real-checkbox').each(function () {
        this.style.display = authCheckbox.checked ? 'none' : '';
      });
      $row.find('.js-dummy-checkbox').each(function () {
        this.style.display = authCheckbox.checked ? '' : 'none';
      });
    }
  };
})(jQuery, Drupal);