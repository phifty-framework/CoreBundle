// Generated by CoffeeScript 1.6.3
(function() {
  var ActionGrowler, _ref,
    __hasProp = {}.hasOwnProperty,
    __extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; };

  ActionGrowler = (function(_super) {
    __extends(ActionGrowler, _super);

    function ActionGrowler() {
      _ref = ActionGrowler.__super__.constructor.apply(this, arguments);
      return _ref;
    }

    ActionGrowler.prototype.init = function(action) {
      var _this = this;
      ActionGrowler.__super__.init.call(this, action);
      return $(action).bind('action.on_result', function(ev, resp) {
        if (resp.success) {
          _this.growl(resp.message, _this.config.success);
        } else {
          _this.growl(resp.message, $.extend(_this.config.error, {
            theme: 'error'
          }));
        }
        return true;
      });
    };

    ActionGrowler.prototype.growl = function(text, opts) {
      return $.jGrowl(text, opts);
    };

    return ActionGrowler;

  })(ActionPlugin);

  window.ActionGrowler = ActionGrowler;

}).call(this);
