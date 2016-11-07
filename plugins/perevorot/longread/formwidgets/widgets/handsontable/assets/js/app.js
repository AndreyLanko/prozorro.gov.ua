+function ($) {
    'use strict';

    var APP = (function () {
        var saveDataInField = function () {

        };

        var initHandsontable = function () {
            $("[data-init-handsontable]").each(function () {
                var self = $(this);
                var id = self.attr('id');
                var data = null;
                var field = $("#" + id + "_field");
                var settings;
                var container = document.getElementById(id);
                var handsontable = null;
                var rowHeaders = false;
                var colHeaders = false;

                if (!field.data('content')) {
                     data = JSON.parse(JSON.stringify([
                         [
                             '', '', ''
                         ],
                         [
                             '', '', ''
                         ],
                         [
                             '', '', ''
                         ],
                         [
                             '', '', ''
                         ]
                     ]));
                } else {
                    data = field.data('content');
                }

                if (field.data('rows')) {
                    rowHeaders = field.data('rows');
                }

                if (field.data('columns')) {
                    colHeaders = field.data('columns');
                }

                settings = {
                    data: data,
                    rowHeaders: rowHeaders,
                    colHeaders: colHeaders,
                    contextMenu: true,
                    mergeCells: true,
                };
                handsontable = new Handsontable(container, settings);
                handsontable.addHook('afterChange', function () {
                    field.val(JSON.stringify(handsontable.getData()));
                });

                self.removeAttr('data-init-handsontable');
            });
        };

        return {
            init: function () {
                setInterval(initHandsontable, 1000);
            }
        }
    });

    $(document).ready(function () {
        APP().init();
    });
}(window.jQuery);