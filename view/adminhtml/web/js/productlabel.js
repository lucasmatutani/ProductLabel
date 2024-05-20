define([
    "jquery",
    "jquery/ui",
    'jquery/jquery.cookie'
], function ($) {
    $.widget('mage.lofProductLabelType', {
        _create: function () {
            var self = this;

            this.element.each(function () {
                var $el = $(this);
                $el.on('click', function () {
                    self.selectType($(this));
                })
            })

            $(document).ready(function () {
                self.element.each(function () {
                    var $el = $(this);
                    if ($el.is(":checked")) {
                        $el.click();
                    }
                })
            });
        },

        selectType: function ($el) {
            var value = $el.val();
            var name = $el.attr('name');
            var field_type = name.replace('_type', '');
            var field_shape = $('.field-' + field_type + '_shape');
            var field_image = $('.field-' + field_type + '_image');
            var field_label_size = $('.field-' + field_type + '_image_size');
            var field_label_color = $('.field-' + field_type + '_label_color');

            if ( value == 'text_only' ) {
                field_label_size.hide()
                field_shape.hide()
                field_image.hide()
                field_label_color.hide()
            } else if ( value == 'shape' ) {
                field_shape.show()
                field_label_size.show()
                field_label_color.show()
                field_image.hide()
            } else if ( value == 'image' ) {
                field_image.show()
                field_label_size.show()
                field_shape.hide()
                field_label_color.hide()
            }
        }
    });

    $.widget('mage.lofProductLabelPosition', {
        name: null,
        element: null,
        table: null,
        tds: null,
        positionClasses: [
            'top-left', 'top-center', 'top-right', 'middle-left', 'middle-center',
            'middle-right', 'bottom-left', 'bottom-center', 'bottom-right'
        ],

        _create: function () {
            this.name = this.element.attr('id').replace('', '');
            this.table = $('#lofproductlabel-table-' + this.name);
            if (!this.table.length || !this.element.length) {
                return;
            }

            var self = this;
            this.tds = this.table.find('td');
            this.table.on("click", "td", function () {
                self.selectTdPosition(this);
            });

            var currentValue = this.element.val();
            if (currentValue) {
                var td = this.getElementByIndex(parseInt(currentValue));
                $(td).addClass('selected');
            }
        },

        selectTdPosition: function (item) {
            var value = this.index(item, 1) - 1;
            if (value >= 0) {
                this.element.val(value);
                this.tds.removeClass('selected');

                $(item).addClass('selected');
            }
        },

        getElementByIndex: function (currentValue) {
            var col = Math.floor(currentValue / 3),
                cell = currentValue % 3,
                element = this.table.find('tr:nth-child(' + (col + 1) + ') td:nth-child(' + (cell + 1) + ')')[0];

            return element;
        },

        index: function (node, parent) {
            var index = 0;
            var siblings = node.parentNode.childNodes;
            for (var j in siblings) {
                if (siblings.hasOwnProperty(j)) {
                    if (siblings[j].nodeType != Node.ELEMENT_NODE) {
                        continue;
                    }
                    ++index;
                    if (siblings[j] == node) {
                        break;
                    }
                }
            }
            if (parent) {
                index += (this.index(node.parentNode, 0) - 1) * 3;
            }

            return index || -1;
        }
    });

    $.widget('mage.lofLabeltabs', {
        _create: function () {
            $('body').on("click", 'a.tab-item-link', function () {
                var id = $(this).attr('id');
                $('label_open_tab_input').val(id);
                $.cookie("lofproductlabel_label_current_tab", id);

                if ($(this)[0].name.indexOf("category") !== -1) {
                    $('#label_cat_image_size').blur();
                } else if ($(this)[0].name.indexOf("product") !== -1) {
                    $('#label_product_image_size').blur();
                }
            });
        }
    });
});
