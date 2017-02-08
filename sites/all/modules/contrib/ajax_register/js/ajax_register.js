
(function($) {
  
  Drupal.behaviors.ajax_register = {
    attach: function(context, settings) {
      // Redirect if yelmoredirect property is set
      if (settings.ajax_register_reload) {
        window.location.reload();
      }
    },
  };
  
}) (jQuery);
