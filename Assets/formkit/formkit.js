/*
$(document.body).ready(function() {
    FormKit.install();
    FormKit.initialize(document.body);
});

Inside Ajax Region:

    $(document.body).ready(function() {
        FormKit.initialize( div element );
    });

*/
var FormKit = {
    register: function(initHandler, installHandler) { 
        if (window.console) {
          console.debug('formkit.register');
        }
        $(FormKit).bind('formkit.initialize',initHandler);
        if (installHandler) {
            $(this).bind('formkit.install',installHandler);
        }
    },
    initialize: function(scopeEl) {
        if (!scopeEl) {
            scopeEl = document.body;
        }
        if (window.console) {
          console.debug('formkit.initialize');
        }
        jQuery(FormKit).trigger('formkit.initialize',[scopeEl]);
    },
    install: function() {
        if (window.console) {
          console.debug('formkit.install');
        }
        $(FormKit).trigger('formkit.install');
    }
};

