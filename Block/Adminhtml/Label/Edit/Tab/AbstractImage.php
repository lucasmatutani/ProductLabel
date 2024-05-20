<?php

namespace Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;

class AbstractImage extends Generic implements TabInterface {
	/**
	 * @var \Lof\ProductLabel\Helper\Config
	 */
	private $helper;

	/**
	 * AbstractImage constructor.
	 *
	 * @param \Magento\Backend\Block\Template\Context $context
	 * @param \Magento\Framework\Registry             $registry
	 * @param \Magento\Framework\Data\FormFactory     $formFactory
	 * @param \Lof\ProductLabel\Helper\Config         $helper
	 * @param array                                   $data
	 */
	public function __construct(
		Context $context,
		Registry $registry,
		FormFactory $formFactory,
		\Lof\ProductLabel\Helper\Config $helper,
		array $data = []
	)
	{
		$this->helper = $helper;
		parent::__construct( $context, $registry, $formFactory, $data );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTabLabel()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTabTitle()
	{
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function canShowTab()
	{
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isHidden()
	{
		return false;
	}

	/**
	 * Prepare form before rendering HTML
	 *
	 * @return $this
	 */
	protected function _prepareForm()
	{
		return parent::_prepareForm();
	}

	/**
	 * @param $field
	 * @param $img
	 * @return string
	 */
	protected function getImageHtml( $field, $img )
	{
		$html = '';
		if ( $img ) {
			$html .= '<p style="margin-top: 5px">';
			$html .= '<img style="max-width:300px" src="' . $this->helper->getImageUrl( $img ) . '" />';
			$html .= '<br/><input type="checkbox" value="1" name="remove_' . $field . '"/> ' . __( 'Remove' );
			$html .= '<input type="hidden" value="' . $img . '" name="old_' . $field . '"/>';
			$html .= '</p>';
		}

		return $html;
	}

	/**
	 * @return string
	 */
	protected function getLabelTextNote()
	{
		return '<strong>{price}</strong> - regular price;<br/>
                <strong>{save_percent}</strong> - save percent;<br/>
                <strong>{save_amount}</strong> - save amount;<br/>
                <strong>{special_price}</strong> - special price;<br/>
                <strong>{attr:code}</strong> - attribute value, e.x: {attr:color};<br/>
                <strong>{spdl}</strong> - days left for special price;<br/>
                <strong>{sphl}</strong> - hours left for special price;<br/>
                <strong>{new_for}</strong> - days ago the product was added;<br/>
                <strong>{sku}</strong> - product SKU;<br/>
                <strong>{stock}</strong> - product qty;<br/>
                <strong>{br}</strong> - new line;<br/>';
	}

	/**
	 * @param $field
	 * @return string
	 */
	protected function getPositionHtml( $field )
	{
		$html = '<table id="lofproductlabel-table-' . $field . '" class="lofproductlabel-table-position">
            <tr><td></td><td></td><td></td></tr>
            <tr><td></td><td></td><td></td></tr>
            <tr><td></td><td></td><td></td></tr>
            </table>';
		$html .= '<script>
            require([
              "jquery",
              "Lof_ProductLabel/js/productlabel"
            ], function ($) {
               $("#' . $field . '").lofProductLabelPosition();
            });
        </script>';

		return $html;
	}

	protected function _restoreSizeColor( $data )
	{
		if ( array_key_exists( 'prod_style', $data ) && $data['prod_style'] ) {
			$prodStyles = $data['prod_style'];

			$template = '@font-size: (.*?);@s';
			preg_match_all( $template, $prodStyles, $res );
			if ( isset( $res[1] ) && isset( $res[1][0] ) ) {
				$data['prod_size'] = $res[1][0];
			}

			$template = '@(^|[^-])color:\s*(.*?);@s';
			preg_match_all( $template, $prodStyles, $res );
			if ( isset( $res[2][0] ) ) {
				$data['prod_color'] = $res[2][0];
			}
		}

		if ( array_key_exists( 'cat_style', $data ) && $data['cat_style'] ) {
			$catStyles = $data['cat_style'];

			$template = '@font-size: (.*?);@s';
			preg_match_all( $template, $catStyles, $res );
			if ( isset( $res[1] ) && isset( $res[1][0] ) ) {
				$data['cat_size'] = $res[1][0];
			}

			$template = '@(^|[^-])color:\s*(.*?);@s';
			preg_match_all( $template, $catStyles, $res );
			if ( isset( $res[2][0] ) ) {
				$data['cat_color'] = $res[2][0];
			}
		}

		return $data;
	}

    /**
     * @param $field
     *
     * @return string
     */
    protected function getJsHtml( $field )
    {
        $html = '<script>
            require([
              "jquery",
              "Lof_ProductLabel/js/productlabel"
            ], function ($) {
               $("[name='.$field.']").lofProductLabelType();
            });
        </script>';

        return $html;
    }
}
