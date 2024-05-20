<?php

namespace Lof\ProductLabel\Block\Adminhtml\Data\Form\Element;

/**
 * Class Color
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Data\Form\Element
 */
class Color extends \Magento\Framework\Data\Form\Element\Text
{
	/**
	 * @return mixed|string
	 */
    public function getAfterElementHtml()
    {
        $html = parent::getAfterElementHtml();
        $value = '#' . $this->getValue();
        $html .= '<script type="text/javascript">
            require( [
                "jquery",
                "jquery/colorpicker/js/colorpicker"
            ] , function ($) {
                    var $el = $("#' . $this->getHtmlId() . '");
                    $el.css("backgroundColor", "'. $value .'");

                    $el.ColorPicker({
                        color: "'. $value .'",
                        onChange: function (hsb, hex, rgb) {
                            $el.css("backgroundColor", "#" + hex).val("#" + hex);
                        }
                    });
            });
        </script>';

        return $html;
    }
}
