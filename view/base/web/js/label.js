define([
    "jquery",
    "jquery/ui",
    'underscore'
], function ($, ui, _) {

    $.widget('mage.lofShowLabel', {
        options: {},
        textElement: null,
        image: null,
        imageWidth: null,
        imageHeight: null,
        parent: null,

        _create: function () {
            this.element = $(this.element);

            if (this.options.move == '1') {
                var items = this.element.parent().find(this.getPriceSelector());
                if (items.length) {
                    var priceContainer = items.first(),
                        newParent = this.getNewParent(priceContainer);

                    if (newParent && newParent.length) {
                        priceContainer.attr('label-observered-' + this.options.label, '1');
                        newParent.append(this.element);
                    } else {
                        this.element.hide();
                        return;
                    }
                } else {
                    this.element.hide();
                    return;
                }
            }

            this.image       = this.element.find('.lofproductlabel-label-image');
            this.textElement = this.element.find('.lofproductlabel-label-text');
            this.parent      = this.element.parent();

            if (!this.image.length) {
                this.setStyleIfNotExist(
                    this.element,
                    {
                        'width': '100px',
                        'height': '50px'
                    }
                );
            }

            /* Move label*/
            if (this.options.path && this.options.path != "") {
                var newParent = this.parent.find(this.options.path);
                if (newParent.length) {
                    this.parent = newParent;
                    newParent.append(this.element);
                }
            }

            /* Set position*/
            if (!(this.parent.css('position') == 'absolute' || this.parent.css('position') == 'relative')) {
                this.parent.css('position', 'relative');
            }
            if (this.parent.prop("tagName") == "A") {
                this.parent.css('display', 'block');
            }

            $('.product-item-info').css('zIndex', '2000');

            /* Get default image size*/
            if (this.imageLoaded(this.image)) {
                var me = this;
                this.image.load(function () {
                    me.element.fadeIn();
                    me.imageWidth = this.naturalWidth;
                    me.imageHeight = this.naturalHeight;
                });
            } else {
                this.element.fadeIn();
                if (this.image[0]) {
                    this.imageWidth = this.image[0].naturalWidth;
                    this.imageHeight = this.image[0].naturalHeight;
                }
            }

            this.setLabelPosition();
            this.setLabelStyle();
            this.productPageZoomEvent();
            this.createResizeEvent();
        },

        createResizeEvent: function () {
            $(window).on('resize', _.debounce(function () {
                this.reloadParentSize();
            }.bind(this), 300));

            $(window).on('orientationchange', function () {
                this.reloadParentSize();
            }.bind(this));
        },

        imageLoaded: function (img) {
            if (!img.complete
                || (typeof img.naturalWidth !== "undefined" && img.naturalWidth === 0)
            ) {
                return false;
            }

            return true;
        },

        productPageZoomEvent: function () {
            if (this.options.mode === 'product') {
                var loflabelObject = this;

                $(document).on('fotorama:load', function (event) {
                    if (loflabelObject
                        && loflabelObject.options.path
                        && loflabelObject.options.mode === 'product'
                        && loflabelObject.options.path !== ""
                    ) {
                        var newParent = loflabelObject.parent.find(loflabelObject.options.path),
                            elementToMove = null;
                        if (newParent.length && newParent != loflabelObject.parent) {
                            loflabelObject.parent.css('position', '');
                            loflabelObject.parent = newParent;

                            elementToMove = loflabelObject.element.parent().hasClass('loflabel-position-wrapper')
                                ? loflabelObject.element.parent()
                                : loflabelObject.element;
                            newParent.append(elementToMove);
                            newParent.css('position', 'relative');
                        }
                    }
                });

                var amastyGallery = $('[data-gallery-role="amasty-main-container"]');
                if (amastyGallery.length) {
                    loflabelObject.parent = amastyGallery;
                    amastyGallery.append(loflabelObject.element.parent());
                    amastyGallery.css('position', 'relative');
                }

                $(window).resize(function () {
                    setTimeout(
                        function () {
                            loflabelObject.setLabelStyle();
                            loflabelObject.setLabelPosition();
                        } ,
                        500
                    );
                });
            }
        },

        setStyleIfNotExist: function (element, styles) {
            for (style in styles) {
                var current = element.attr('style');
                if (!current ||
                    (current.indexOf('; ' + style) == -1 && current.indexOf(';' + style) == -1)
                ) {
                    element.css(style, styles[style]);
                }
            }

        },

        setLabelStyle: function () {
            var display = this.options.alignment === '1' ? 'inline-block' : 'block';

            this.setStyleIfNotExist(
                this.textElement,
                {
                    'padding': '0 3px',
                    'position': 'absolute',
                    'box-sizing': 'border-box',
                    'white-space': 'nowrap',
                    'width': '100%'
                }
            );

            if (this.image.length) {
                this.image.css({'width': '100%'});

                if (this.options.size > 0) {
                    var parentWidth = parseInt(this.parent.css('width').replace(/\D+/g, ""));
                    if (parentWidth) {
                        this.imageWidth = parentWidth * this.options.size / 100;
                    }
                } else {
                    this.imageWidth = this.imageWidth + 'px';
                }
                this.setStyleIfNotExist(this.element, {'width': this.imageWidth});
                this.imageHeight = this.image.height();

                /* If container doesn't load(height = 0 ) set proportional height*/
                if (!this.imageHeight && this.image[0] && 0 != this.image[0].naturalWidth) {
                    var tmpWidth = this.image[0].naturalWidth;
                    var tmpHeight = this.image[0].naturalHeight;
                    this.imageHeight = parseFloat(this.imageWidth) * (tmpHeight / tmpWidth);
                }
                var lineCount = this.textElement.html().split('<br>').length;
                lineCount = (lineCount >= 1) ? lineCount : 1;
                this.textElement.css('lineHeight', this.imageHeight/lineCount + 'px');

                this.setStyleIfNotExist(this.element, {
                    'height': this.imageHeight + 'px'
                });

                if (this.options.size) {
                    // Set responsive font size
                    var flag = 1;
                    this.textElement.css({'width': 'auto'});
                    while (this.textElement.width() > 0.9 * this.textElement.parent().width() && flag++ < 15) {
                        this.textElement.css({'fontSize': (100 - flag * 5) + '%'});
                    }
                    this.textElement.css({'width': '100%'});
                }
            }

            this.element.parent().css({
                'line-height': 'normal',
                'position': 'absolute',
                'z-index': 995
            });

            this.setStyleIfNotExist(
                this.element,
                {
                    'position': 'relative',
                    'display': display
                }
            );

            this.element.click(function () {
                $(this).parent().trigger('click');
            });

            this.reloadParentSize();
        },

        setPosition: function (position) {
            this.options.position = position;
            this.setLabelPosition();
            this.reloadParentSize();
        },

        setStyle: function () {
            this.setLabelStyle();
        },

        reloadParentSize: function () {
            var parent = this.element.parent(),
                height = null,
                width = 5;

            parent.css({
                'position' : 'relative',
                'display' : 'inline-block',
                'width' : 'auto',
                'height' : 'auto'
            });
            height = parent.height();

            if (this.options.alignment === '1') {
                parent.children().each(function (index, element) {
                    width += $(element).width() + parseInt($(element).css('margin-left'))
                        + parseInt($(element).css('margin-right'));
                });
            } else {
                width = parent.width();
            }

            parent.css({
                'position': 'absolute',
                'display': 'block',
                'height' : height ? height + 'px' : '',
                'width' : width ? width + 'px' : ''
            });
        },

        getWidgetLabelCode: function() {
            var label = '';
            if (this.element.parents('.widget-product-grid, .widget').length) {
                label = 'widget';
            }

            return label;
        },

        setLabelPosition: function () {
            var className = 'loflabel-position-' + this.options.position
                + '-' + this.options.product+ '-' + this.options.mode + this.getWidgetLabelCode(),
                wrapper = this.parent.find('.' + className);

            if (wrapper.length) {
                wrapper.append(this.element);

                if (this.options.alignment === '1') {
                    this.setStyleIfNotExist(
                        this.element,
                        {
                            'marginLeft': this.options.margin + 'px'
                        }
                    );
                } else {
                    this.setStyleIfNotExist(
                        this.element,
                        {
                            'marginTop': this.options.margin + 'px'
                        }
                    );
                }
            } else {
                var parent = this.element.parent();
                if (parent.hasClass('loflabel-position-wrapper')) {
                    parent.parent().append(this.element);
                }

                this.element.wrap("<div class='" + className + ' loflabel-position-wrapper' + "'></div>");
                wrapper = this.element.parent();
            }

            // Clear styles before changing
            wrapper.css({
                'top'  : "",
                'left' : "",
                'right' : "",
                'bottom' : "",
                'margin-top' : "",
                'margin-bottom' : "",
                'margin-left' : "",
                'margin-right' : ""
            });

            switch (this.options.position) {
                case 'top-left':
                    wrapper.css({
                        'top'  : 0,
                        'left' : 0
                    });
                    break;
                case 'top-center':
                    wrapper.css({
                        'top': 0,
                        'left': 0,
                        'right': 0,
                        'margin-left': 'auto',
                        'margin-right': 'auto'
                    });
                    break;
                case 'top-right':
                    wrapper.css({
                        'top'   : 0,
                        'right' : 0,
                        'text-align' : 'right'
                    });
                    break;

                case 'middle-left':
                    wrapper.css({
                        'left' : 0,
                        'top'   : 0,
                        'bottom'  : 0,
                        'margin-top': 'auto',
                        'margin-bottom': 'auto'
                    });
                    break;
                case 'middle-center':
                    wrapper.css({
                        'top'   : 0,
                        'bottom'  : 0,
                        'margin-top': 'auto',
                        'margin-bottom': 'auto',
                        'left': 0,
                        'right': 0,
                        'margin-left': 'auto',
                        'margin-right': 'auto'
                    });
                    break;
                case 'middle-right':
                    wrapper.css({
                        'top'   : 0,
                        'bottom'  : 0,
                        'margin-top': 'auto',
                        'margin-bottom': 'auto',
                        'right' : 0,
                        'text-align' : 'right'
                    });
                    break;

                case 'bottom-left':
                    wrapper.css({
                        'bottom'  : 0,
                        'left'    : 0
                    });
                    break;
                case 'bottom-center':
                    wrapper.css({
                        'bottom': 0,
                        'left': 0,
                        'right': 0,
                        'margin-left': 'auto',
                        'margin-right': 'auto'
                    });
                    break;
                case 'bottom-right':
                    wrapper.css({
                        'bottom'   : 0,
                        'right'    : 0,
                        'text-align' : 'right'
                    });
                    break;
            }
        },

        getNewParent: function (item) {
            var imageContainer = null,
                productContainer = item.parents('.item').first();

            if (!productContainer.length) {
                productContainer = item.parents('.product-item');
            }

            if (productContainer && productContainer.length) {
                imageContainer = productContainer.find(this.options.path).first();
            }

            return imageContainer;
        },

        setLabelSize: function (size) {
            this.options.size = size;
        },

        getPriceSelector: function () {
            var notLabelObservered = ':not([label-observered-' + this.options.label + '])',
                selector = '[data-product-id="' + this.options.product + '"]' + notLabelObservered + ', ' +
                    '[id="product-price-' + this.options.product + '"]' + notLabelObservered + ', ' +
                    '[name="product"][value="' + this.options.product + '"]' + notLabelObservered;

            return selector;
        }
    });

    return $.mage.lofShowLabel;
});



