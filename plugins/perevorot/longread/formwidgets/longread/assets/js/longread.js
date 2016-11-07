/*
 * Field longread plugin
 *
 * Data attributes:
 * - data-control="fieldlongread" - enables the plugin on an element
 * - data-option="value" - an option with a value
 *
 * JavaScript API:
 * $('a#someElement').fieldlongread({ option: 'value' })
 *
 * Dependences:
 * - Some other plugin (filename.js)
 */

+function ($) { "use strict";

    // FIELD longread CLASS DEFINITION
    // ============================

    var longread = function(element, options) {
        this.options   = options
        this.$el       = $(element)

        // Init
        this.init()
    }

    longread.DEFAULTS = {
        option: 'default'
    }

    longread.prototype.init = function() {
        // Init with no arguments
        this.bindSorting()
    }

    longread.prototype.bindSorting = function() {

        var sortableOptions = {
            // useAnimation: true,
            handle: '.longread-item-handle',
            nested: false
        }

        $('ul.field-longread-items', this.$el).sortable(sortableOptions)
    }

    longread.prototype.unbind = function() {
        this.$el.find('ul.field-longread-items').sortable('destroy')
        this.$el.removeData('oc.longread')
    }

    // FIELD longread PLUGIN DEFINITION
    // ============================

    var old = $.fn.fieldlongread

    $.fn.fieldlongread = function (option) {
        var args = Array.prototype.slice.call(arguments, 1), result
        this.each(function () {
            var $this   = $(this)
            var data    = $this.data('oc.longread')
            var options = $.extend({}, longread.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.longread', (data = new longread(this, options)))
            if (typeof option == 'string') result = data[option].apply(data, args)
            if (typeof result != 'undefined') return false
        })

        return result ? result : this
    }

    $.fn.fieldlongread.Constructor = longread

    // FIELD longread NO CONFLICT
    // =================

    $.fn.fieldlongread.noConflict = function () {
        $.fn.fieldlongread = old
        return this
    }

    // FIELD longread DATA-API
    // ===============

    $(document).render(function() {
        $('[data-control="fieldlongread"]').fieldlongread()
    });

    $(document).ready(function() {
        var field=$('[data-field-name="longread"]');

        field.closest('.tab-pane').removeClass('padded-pane');
        field.find('.form-tabless-fields').removeClass('form-tabless-fields');

        $("[longread-nav]").sticky({
            topSpacing: 0,
            zIndex: 1000,
            getWidthFrom: '#Form'
        });
    });

}(window.jQuery);
