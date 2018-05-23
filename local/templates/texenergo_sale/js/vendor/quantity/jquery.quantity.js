/**
 * Quantity
 * ...
 *
 * Dual licensed under the MIT and GPL licenses.
 * Copyright (c) 2013 Maxim Timokhin
 * @name     Quantity
 * @author   Maxim Timokhin (m.v.timokhin@gmail.com)
 */

(function ($) {

    var Quantity = function (element, options) {
        this.container = $('<div class="quantity-container"><span class="minus">-</span><input type="number" class="value" pattern="[0-9]*" value="0" maxlength="5" max="99999"><span class="plus">+</span></div>');
        this.el = $(element);
        if (this.el.hasClass('jq-quantity')) return;
        this.init(options);
    };

    Quantity.prototype = {
        init: function (options) {
            var that = this;

            that.options = $.extend({}, that.defaults, options);
            that.oldval = that.el.val();
            that.curval = that.el.val();
            $(this.options.value, this.container).val(this.curval);
            that.options.minValue = that.el.attr('min') || that.options.minValue;
            that.options.maxValue = that.el.attr('max') || that.options.maxValue;


            var el_hidden = $('<input/>', {
                'type': 'hidden',
            });

            var attributes = that.el.prop("attributes");

            $.each(attributes, function () {
                if (this.name == 'type') return;
                el_hidden.attr(this.name, this.value);
            });

            el_hidden.addClass('jq-quantity');

            that.el.after(el_hidden);
            that.el.remove();
            that.el = el_hidden;

            that.el.after(that.container);
            //that.el.attr('type', 'hidden');


            that.container.attr('unselectable', 'on')
                .css('user-select', 'none')
                .on('selectstart', false);

            that.container.find(that.options.plus).on('click', function (e) {
                that.curval++;
                that.refresh();
                e.preventDefault();
                return false;
            });

            that.container.find(that.options.minus).on('click', function (e) {
                if (that.curval > that.options.minValue) {
                    that.curval--;
                    that.refresh();
                }
                e.preventDefault();
                return false;
            });

            that.el.on('qty:change', function () {
                that.curval = that.el.val();
                that.refresh();
            });

            var holdTimeout, holdTimer;
            that.container.find(that.options.plus).on('mousedown', function (e) {
                holdTimeout = setTimeout(function () {
                    holdTimer = setInterval(function () {
                        if (that.curval >= that.options.maxValue) {
                            clearInterval(holdTimer);
                            return false;
                        }
						if(that.attr('busy') != 'y'){
							that.curval++;
							that.refresh();
						}
                    }, 100);
                }, 500);
            });
            that.container.find(that.options.minus).on('mousedown', function (e) {
                holdTimeout = setTimeout(function () {
                    holdTimer = setInterval(function () {
                        if (that.curval <= that.options.minValue) {
                            clearInterval(holdTimer);
                            return false;
                        }
                        if(that.attr('busy') != 'y'){
							that.curval--;
							that.refresh();
						}
                    }, 100);
                }, 500);
            });

            $('body').on('mouseup', function (e) {
                clearInterval(holdTimeout);
                clearInterval(holdTimer);
            });


            $(this.options.value, this.container).on('keyup', function () {
                var val = $(this).val().replace(/[^\d+]/g, '');
                //if (!val.length || val <= 0) val = 1;
                that.curval = val;
                $(this).val(val);
                that.refresh();
            });

            that.refresh();
        },

        refresh: function () {
            if (this.oldval != this.curval) {
                $(this.options.value, this.container).val(this.curval);
                this.container.trigger('change', [this.curval]);
                this.el.val(this.curval);
                this.el.trigger('change');
                this.oldval = this.curval;
            }
        },

        value: function () {
            return this.curval;
        }
    };

    Quantity.prototype.defaults = {
        current: 1,
        minValue: 1,
        maxValue: 9999,
        plus: '.plus',
        minus: '.minus',
        value: '.value'
    };

    $.fn.Quantity = function (options) {
        var args = Array.prototype.slice.call(arguments, 1);

        return this.each(function () {
            var $this = $(this)
                , data = $this.data('Quantity')
            if (!data) $this.data('Quantity', (data = new Quantity(this, options)));
            if (typeof options == 'string') return data[options].apply(data, args);
        })

    }

    $(function () {
        $('input.quantity').Quantity();
    });

})(jQuery);