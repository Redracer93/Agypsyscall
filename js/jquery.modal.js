/*
    A simple jQuery modal (http://github.com/kylefox/jquery-modal)
    Version 0.8.0
*/
(function (factory) {
// console.log('modal definido')
  // Making your jQuery plugin work better with npm tools
  // http://blog.npmjs.org/post/112712169830/making-your-jquery-plugin-work-better-with-npm
  if(typeof module === "object" && typeof module.exports === "object") {
    factory(require("jquery"), window, document);
  }
  else {
    factory(jQuery, window, document);
  }
}(function($, window, document, undefined) {

  var ammodals = [],
      getCurrent = function() {
        return ammodals.length ? ammodals[ammodals.length - 1] : null;
      },
      selectCurrent = function() {
        var i,
            selected = false;
        for (i=ammodals.length-1; i>=0; i--) {
          if (ammodals[i].$blocker) {
            ammodals[i].$blocker.toggleClass('current',!selected).toggleClass('behind',selected);
            selected = true;
          }
        }
      };

  $.ammodal = function(el, options) {
    var remove, target;
    this.$body = $('body');
    this.options = $.extend({}, $.ammodal.defaults, options);
    this.options.doFade = !isNaN(parseInt(this.options.fadeDuration, 10));
    this.$blocker = null;
    if (this.options.closeExisting)
      while ($.ammodal.isActive())
        $.ammodal.close(); // Close any open ammodals.
    ammodals.push(this);
    if (el.is('a')) {
      target = el.attr('href');
      //Select element by id from href
      if (/^#/.test(target)) {
        this.$elm = $(target);
        if (this.$elm.length !== 1) return null;
        this.$body.append(this.$elm);
        this.open();
      //AJAX
      } else {
        this.$elm = $('<div>');
        this.$body.append(this.$elm);
        remove = function(event, modal) { modal.elm.remove(); };
        this.showSpinner();
        el.trigger($.ammodal.AJAX_SEND);
        $.get(target).done(function(html) {
          if (!$.ammodal.isActive()) return;
          el.trigger($.ammodal.AJAX_SUCCESS);
          var current = getCurrent();
          current.$elm.empty().append(html).on($.ammodal.CLOSE, remove);
          current.hideSpinner();
          current.open();
          el.trigger($.ammodal.AJAX_COMPLETE);
        }).fail(function() {
          el.trigger($.ammodal.AJAX_FAIL);
          var current = getCurrent();
          current.hideSpinner();
          ammodals.pop(); // remove expected modal from the list
          el.trigger($.ammodal.AJAX_COMPLETE);
        });
      }
    } else {
      this.$elm = el;
      this.$body.append(this.$elm);
      this.open();
    }
  };

  $.ammodal.prototype = {
    constructor: $.ammodal,

    open: function() {
      var m = this;
      this.block();
      if(this.options.doFade) {
        setTimeout(function() {
          m.show();
        }, this.options.fadeDuration * this.options.fadeDelay);
      } else {
        this.show();
      }
      $(document).off('keydown.ammodal').on('keydown.ammodal', function(event) {
        var current = getCurrent();
        if (event.which == 27 && current.options.escapeClose) current.close();
      });
      if (this.options.clickClose)
        this.$blocker.click(function(e) {
          if (e.target==this)
            $.ammodal.close();
        });
    },

    close: function() {
      ammodals.pop();
      this.unblock();
      this.hide();
      if (!$.ammodal.isActive())
        $(document).off('keydown.ammodal');
    },

    block: function() {
      this.$elm.trigger($.ammodal.BEFORE_BLOCK, [this._ctx()]);
      this.$body.css('overflow','hidden');
      this.$blocker = $('<div class="jquery-ammodal am-blocker current"></div>').appendTo(this.$body);
      selectCurrent();
      if(this.options.doFade) {
        this.$blocker.css('opacity',0).animate({opacity: 1}, this.options.fadeDuration);
      }
      this.$elm.trigger($.ammodal.BLOCK, [this._ctx()]);
    },

    unblock: function(now) {
      if (!now && this.options.doFade)
        this.$blocker.fadeOut(this.options.fadeDuration, this.unblock.bind(this,true));
      else {
        this.$blocker.children().appendTo(this.$body);
        this.$blocker.remove();
        this.$blocker = null;
        selectCurrent();
        if (!$.ammodal.isActive())
          this.$body.css('overflow','');
      }
    },

    show: function() {
      this.$elm.trigger($.ammodal.BEFORE_OPEN, [this._ctx()]);
      if (this.options.showClose) {
        this.closeButton = $('<a href="#am-close-modal" rel="ammodal:close" class="am-close-modal ' + this.options.closeClass + '">' + this.options.closeText + '</a>');
        this.$elm.append(this.closeButton);
      }
      this.$elm.addClass(this.options.modalClass).appendTo(this.$blocker);
      if(this.options.doFade) {
        this.$elm.css('opacity',0).show().animate({opacity: 1}, this.options.fadeDuration);
      } else {
        this.$elm.show();
      }
      this.$elm.trigger($.ammodal.OPEN, [this._ctx()]);
    },

    hide: function() {
      this.$elm.trigger($.ammodal.BEFORE_CLOSE, [this._ctx()]);
      if (this.closeButton) this.closeButton.remove();
      var _this = this;
      if(this.options.doFade) {
        this.$elm.fadeOut(this.options.fadeDuration, function () {
          _this.$elm.trigger($.ammodal.AFTER_CLOSE, [_this._ctx()]);
        });
      } else {
        this.$elm.hide(0, function () {
          _this.$elm.trigger($.ammodal.AFTER_CLOSE, [_this._ctx()]);
        });
      }
      this.$elm.trigger($.ammodal.CLOSE, [this._ctx()]);
    },

    showSpinner: function() {
      if (!this.options.showSpinner) return;
      this.spinner = this.spinner || $('<div class="' + this.options.modalClass + '-spinner"></div>')
        .append(this.options.spinnerHtml);
      this.$body.append(this.spinner);
      this.spinner.show();
    },

    hideSpinner: function() {
      if (this.spinner) this.spinner.remove();
    },

    //Return context for custom events
    _ctx: function() {
      return { elm: this.$elm, $blocker: this.$blocker, options: this.options };
    }
  };

  $.ammodal.close = function(event) {
    if (!$.ammodal.isActive()) return;
    if (event) event.preventDefault();
    var current = getCurrent();
    current.close();
    return current.$elm;
  };

  // Returns if there currently is an active modal
  $.ammodal.isActive = function () {
    return ammodals.length > 0;
  }

  $.ammodal.getCurrent = getCurrent;

  $.ammodal.defaults = {
    closeExisting: true,
    escapeClose: true,
    clickClose: true,
    closeText: 'Close',
    closeClass: '',
    modalClass: "am-modal",
    spinnerHtml: null,
    showSpinner: true,
    showClose: true,
    fadeDuration: null,   // Number of milliseconds the fade animation takes.
    fadeDelay: 1.0        // Point during the overlay's fade-in that the modal begins to fade in (.5 = 50%, 1.5 = 150%, etc.)
  };

  // Event constants
  $.ammodal.BEFORE_BLOCK = 'ammodal:before-block';
  $.ammodal.BLOCK = 'ammodal:block';
  $.ammodal.BEFORE_OPEN = 'ammodal:before-open';
  $.ammodal.OPEN = 'ammodal:open';
  $.ammodal.BEFORE_CLOSE = 'ammodal:before-close';
  $.ammodal.CLOSE = 'ammodal:close';
  $.ammodal.AFTER_CLOSE = 'ammodal:after-close';
  $.ammodal.AJAX_SEND = 'ammodal:ajax:send';
  $.ammodal.AJAX_SUCCESS = 'ammodal:ajax:success';
  $.ammodal.AJAX_FAIL = 'ammodal:ajax:fail';
  $.ammodal.AJAX_COMPLETE = 'ammodal:ajax:complete';

  $.fn.ammodal = function(options){
    if (this.length === 1) {
      new $.ammodal(this, options);
    }
    return this;
  };

  // Automatically bind links with rel="ammodal:close" to, well, close the modal.
  $(document).on('click.ammodal', 'a[rel="ammodal:close"]', $.ammodal.close);
  $(document).on('click.ammodal', 'a[rel="ammodal:open"]', function(event) {
    event.preventDefault();
    $(this).ammodal();
  });
}));
