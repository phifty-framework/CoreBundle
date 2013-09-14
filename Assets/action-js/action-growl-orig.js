var ActionGrowler = ActionPlugin.extend({
    growl: function(text,opts) {
        return $.jGrowl(text,opts);
    },
    onResult: function(ev,resp) {
        if( ! resp.message ) {
            if( resp.error && resp.validations ) {
                var errs = this.extErrorMsgs(resp);
                for ( var i in errs ) {
                    this.growl( errs[i] , { theme: 'error' } );
                }
            }
            return;
        }

        if( resp.success ) {
            this.growl(resp.message , this.config.success);
        } else {
            this.growl(resp.message, $.extend( this.config.error , { theme: 'error' } ));
        }
    }
});
