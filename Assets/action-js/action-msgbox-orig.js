/* 
Action Message Box Plugin 
    
Handle action result, and render the result as html.

TODO:

Move progressbar out as a plugin.
*/
var ActionMsgbox = ActionPlugin.extend({
    load: function() {
        /* if we have form */
        if( ! this.form )
            return;

        /* since we use Phifty::Action::...  ... */
        var actionName = this.action.name;
        var actionId = actionName.replace( /::/g , '-' );

        this.cls    = 'action-' + actionId + '-result';
        this.ccls   = 'action-result';  // common class
        this.div    = this.form.find( '.' + this.cls );
        if( ! this.div.get(0) ) { 
            this.div = $('<div/>').addClass( this.cls ).addClass( this.ccls ).hide();
            this.form.prepend( this.div );
        }

        this.div.empty().hide();
    },

    beforeSubmit: function(ev,d) { 
        if( ! this.form )
            return;
        this.wait();
    },

    onResult: function(ev,resp) { 
        if( ! this.form )
            return;

        var that = this;
        if( resp.success ) {
            var sd = $('<div/>').addClass('success').html(resp.message);
            this.div.html( sd ).fadeIn('slow');
        }
        else if ( resp.error ) {
            this.div.empty();
            var ed = $('<div/>').addClass('errors');
            if( resp.message ) {
                var et = $('<div/>').addClass('error-title').html(resp.message);
                ed.append( et );
            }
            this.div.append( ed ).fadeIn('slow');
        }

        if( resp.validations ) {
            var errs = this.extErrorMsgs(resp);
            $(errs).each(function(i,e) { 
                that.addError(e);
            });
        }
    },


    /* private methods */
    extErrorMsgs: function(resp) {
        var errs = [ ];
        for ( var field in resp.validations ) {
            var v = resp.validations[field];
            if( v.valid == false || v.error )
                errs.push( v.message );
        }
        return errs;
    },

    addError: function(msg) { 
        var d = $('<div/>').addClass('error-message').html(msg);
        this.div.find('.errors').append(d);
    },

    wait: function() { 
        var ws = $('<div/>').addClass('waiting').html( "Progressing" );
        this.div.html( ws ).show();
    }
});
