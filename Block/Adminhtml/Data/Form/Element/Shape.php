<?php

namespace Lof\ProductLabel\Block\Adminhtml\Data\Form\Element;

use Magento\Framework\Escaper;
use Magento\Framework\Data\Form\Element\Factory;
use Magento\Framework\Data\Form\Element\CollectionFactory;

/**
 * Class Shape
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Data\Form\Element
 */
class Shape extends \Magento\Framework\Data\Form\Element\File {
	/**
	 * @var \Lof\ProductLabel\Helper\Shape
	 */
	private $shapeHelper;

	/**
	 * File constructor.
	 *
	 * @param \Magento\Framework\Data\Form\Element\Factory           $factoryElement
	 * @param \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection
	 * @param \Magento\Framework\Escaper                             $escaper
	 * @param \Lof\ProductLabel\Helper\Shape                         $shapeHelper
	 * @param array                                                  $data
	 */
	public function __construct(
		Factory $factoryElement,
		CollectionFactory $factoryCollection,
		Escaper $escaper,
		\Lof\ProductLabel\Helper\Shape $shapeHelper,
		$data = []
	)
	{
		parent::__construct( $factoryElement, $factoryCollection, $escaper, $data );
		$this->shapeHelper = $shapeHelper;
	}

	/**
	 * @return string
	 */
	public function getElementHtml()
	{
		return $this->getShapeHtml();
	}

	/**
	 * @return string
	 */
	private function getShapeHtml()
	{
		$value  = $this->getValue();
		$shapes = $this->shapeHelper->getShapes();

		$html = '<div id="lofproductlabel-shape' . $this->getHtmlId() . '" class="additional">';
		$html .= '<div class="lofproductlabel-shapes-container">';
		foreach ( $shapes as $shape => $shapeName ) {
			$checked = ( $value && strpos( $value, $shape ) !== false ) ? 'checked' : '';
			$html    .= $this->shapeHelper->generateShape( $shape, $this->getHtmlId(), $checked );
		}
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
