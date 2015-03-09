;(function ( $, window, document, undefined ) {

    "use strict";
    var pluginName = "removableProduct",
        defaults = {
            removeProductFromBasketRoute: "",
            productCounterSelector: '.jsProductCounter',
            itemCounterSelector: '.jsItemCounter',
            dataSku: 'sku'
        };

    // The actual plugin constructor
    function removableProduct ( element, options ) {
        this.element = element;
        this.settings = $.extend( {}, defaults, options );
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(removableProduct.prototype, {
        updateCounters: function (productCount, itemCount) {
            $(this.settings.productCounterSelector).text(productCount);
            $(this.settings.itemCounterSelector).text(itemCount);
        },
        removeProductRow: function () {
            $(this.element).closest('tr').fadeOut();
        },
        makeRemoveProductRequest: function () {
            var self = this;
            $.ajax({
                type: 'POST',
                url: this.settings.removeProductFromBasketRoute,
                data: {
                    'sku': $(self.element).closest('tr').data(self.settings.dataSku)
                },
                success: function (data) {
                    self.removeProductRow();
                    self.updateCounters(data.productCount, data.itemCount)
                }
            });
        },
        init: function () {
            var self = this;
            $(this.element).on('click', function (e) {
                e.preventDefault();
                self.makeRemoveProductRequest();
            });
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[ pluginName ] = function ( options ) {
        return this.each(function() {
            if ( !$.data( this, "plugin_" + pluginName ) ) {
                $.data( this, "plugin_" + pluginName, new removableProduct( this, options ) );
            }
        });
    };

})( jQuery, window, document );
