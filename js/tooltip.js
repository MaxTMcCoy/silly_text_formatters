/**
 * tooltip
 */
(function ($) {
  'use strict';
  Drupal.behaviors.silly_text_formatters = {
    attach: function () {
      $('.tooltip[title]').qtip();
    }
  };
})(jQuery);
