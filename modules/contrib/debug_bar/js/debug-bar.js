(function ($) {

  'use strict';

  Drupal.behaviors.debugBar = {
    attach: function () {

      var $debugBar = $('#debug-bar').once('debug-bar');
      if ($debugBar.length !== 1) {
        return;
      }

      var isHidden = $.cookie('debug_bar_hidden') === '1';
      toggleDebugBar(!isHidden);

      $debugBar
        .find('#debug-bar-hide-button')
        .once('debug-bar')
        .click(function (event) {
          isHidden = !isHidden;
          $.cookie('debug_bar_hidden', isHidden ? '1' : '0');
          toggleDebugBar(!isHidden);
          event.preventDefault();
        });

      function toggleDebugBar(show) {
        $debugBar.toggleClass('debug-bar-hidden', !show);
        $debugBar.attr('aria-expanded', show ? 'true' : 'false');
      }
    }
  };

})(jQuery);
