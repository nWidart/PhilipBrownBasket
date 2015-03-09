;
(function ($, window, document, undefined) {

    "use strict";

    var pluginName = "updatableProduct",
        defaults = {
            updateProductCountRoute: "",
            productCounterSelector: '.jsProductCounter',
            dataSku: 'sku',
            countLoaderSelector: '.countLoader'
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
        updateCounters: function (productCount) {
            $(this.settings.productCounterSelector).text(productCount);
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
                    if (data.removed) {
                        self.updateCounters(data.productCount)
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
