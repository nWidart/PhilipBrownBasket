;
(function ($, window, document, undefined) {

    "use strict";

    var pluginName = "buyableProduct",
        defaults = {
            addProductToBasketRoute: "",
            removeProductFromBasketRoute: "",
            dataSku: 'sku',
            dataName: 'name',
            dataPrice: 'price',
            productCounterSelector: '.jsProductCounter',
            addProductClass: 'btn-add',
            removeProductClass: 'btn-remove'
        };

    // The actual plugin constructor
    function buyableProduct(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(buyableProduct.prototype, {
        updateCounters: function (productCount) {
            $(this.settings.productCounterSelector).text(productCount);
        },
        updateButtonClasses: function () {
            $(this.element).toggleClass('btn-info btn-danger');
            $(this.element).toggleClass('btn-add btn-remove');
            $(this.element).html('<i class="fa fa-cart-plus"></i> Remove from cart');
        },
        makeAddProductRequest: function () {
            var self = this;
            $.ajax({
                type: 'POST',
                url: this.settings.addProductToBasketRoute,
                data: {
                    'sku': $(self.element).data(self.settings.dataSku),
                    'name': $(self.element).data(self.settings.dataName),
                    'price': $(self.element).data(self.settings.dataPrice)
                },
                success: function (data) {
                    self.updateButtonClasses();
                    self.updateCounters(data.productCount)
                }
            });
        },
        makeRemoveProductRequest: function () {
            var self = this;
            $.ajax({
                type: 'POST',
                url: this.settings.removeProductFromBasketRoute,
                data: {
                    'sku': $(self.element).data(self.settings.dataSku)
                },
                success: function (data) {
                    self.updateButtonClasses();
                    self.updateCounters(data.productCount)
                }
            });
        },
        init: function () {
            var self = this;
            $(this.element).on('click', function (e) {
                e.preventDefault();
                if ($(this).hasClass(self.settings.addProductClass)) {
                    self.makeAddProductRequest();
                }
                if ($(this).hasClass(self.settings.removeProductClass)) {
                    self.makeRemoveProductRequest();
                }
            });
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new buyableProduct(this, options));
            }
        });
    };

})(jQuery, window, document);
