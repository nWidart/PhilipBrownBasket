;
(function ($, window, document, undefined) {

    "use strict";

    var pluginName = "updatableProduct",
        defaults = {
            updateProductCountRoute: "",
            productCounterSelector: '.jsProductCounter',
            itemCounterSelector: '.jsItemCounter',
            dataSku: 'sku',
            countLoaderSelector: '.countLoader',
            // Row values
            itemPriceSelector: '.jsItemPrice',
            itemTotalTaxSelector: '.jsItemTotalTax',
            itemTotalPriceSelector: '.jsItemTotalPrice',
            // Totals
            subtotalSelector: '.jsSubtotal',
            totalTaxSelector: '.jsTotalTax',
            totalDeliverySelector: '.jsTotalDelivery',
            totalSelector: '.jsTotal'
        };

    // The actual plugin constructor
    function Plugin(element, options) {
        this.element = element;
        this.settings = $.extend({}, defaults, options);
        this._defaults = defaults;
        this._name = pluginName;
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        removeProductRow: function () {
            $(this.element).closest('tr').fadeOut();
        },
        updateCounters: function (productCount, itemCount) {
            $(this.settings.productCounterSelector).text(productCount);
            $(this.settings.itemCounterSelector).text(itemCount);
        },
        updateTotals: function (subtotal, totalTax, totalDelivery, total) {
            $(this.settings.subtotalSelector).text(subtotal);
            $(this.settings.totalTaxSelector).text(totalTax);
            $(this.settings.totalDeliverySelector).text(totalDelivery);
            $(this.settings.totalSelector).text(total);
        },
        updateProductRow: function (itemTotalTax, itemTotal) {
            $(this.element).parent().siblings(this.settings.itemTotalTaxSelector).text(itemTotalTax);
            $(this.element).parent().siblings(this.settings.itemTotalPriceSelector).text(itemTotal);
        },
        makeUpdateProductRequest: function () {
            var self = this;
            $.ajax({
                type: 'POST',
                url: this.settings.updateProductCountRoute,
                data: {
                    'sku': $(self.element).closest('tr').data(self.settings.dataSku),
                    'count': $(self.element).val()
                },
                success: function (data) {
                    self.updateCounters(data.productCount, data.itemCount);
                    self.updateTotals(data.subtotal, data.totalTax, data.totalDelivery, data.total);
                    self.updateProductRow(data.itemTotalTax, data.itemTotal);
                    if (data.removed) {
                        self.removeProductRow();
                    } else {
                        $(self.element).siblings(self.settings.countLoaderSelector).fadeOut();
                    }
                }
            });
        },
        init: function () {
            var self = this;
            $(this.element).on('change', function (e) {
                e.preventDefault();
                $(self.element).siblings(self.settings.countLoaderSelector).show();
                self.makeUpdateProductRequest();
            });
        }
    });

    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations
    $.fn[pluginName] = function (options) {
        return this.each(function () {
            if (!$.data(this, "plugin_" + pluginName)) {
                $.data(this, "plugin_" + pluginName, new Plugin(this, options));
            }
        });
    };

})(jQuery, window, document);
