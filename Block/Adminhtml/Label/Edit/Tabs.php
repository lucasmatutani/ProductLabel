<?php

namespace Lof\ProductLabel\Block\Adminhtml\Label\Edit;

/**
 * Class Tabs
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Label\Edit
 */
class Tabs extends \Magento\Backend\Block\Widget\Tabs {
	/**
	 * @var \Magento\Framework\Stdlib\CookieManagerInterface
	 */
	private $cookieManager;

	/**
	 * Tabs constructor.
	 *
	 * @param \Magento\Backend\Block\Template\Context          $context
	 * @param \Magento\Framework\Json\EncoderInterface         $jsonEncoder
	 * @param \Magento\Backend\Model\Auth\Session              $authSession
	 * @param \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
	 * @param array                                            $data
	 */
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Framework\Json\EncoderInterface $jsonEncoder,
		\Magento\Backend\Model\Auth\Session $authSession,
		\Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
		array $data = []
	)
	{
		$this->cookieManager = $cookieManager;
		parent::__construct( $context, $jsonEncoder, $authSession, $data );
	}

	/**
	 * Constructor
	 *
	 * @return void
	 */
	protected function _construct()
	{
		parent::_construct();
		$this->setId( 'lofproductlabel_label_edit_tabs' );
		$this->setDestElementId( 'edit_form' );
		$this->setTitle( __( 'Label Options' ) );
	}

	public function _beforeToHtml()
	{
		$activeTab = $this->cookieManager->getCookie( 'lofproductlabel_current_tab' );
		if ( $activeTab ) {
			$this->setActiveTab( str_replace( 'lofproductlabel_label_edit_tabs_', '', $activeTab ) );
			$this->setActiveTabId( $activeTab );
		}

		return parent::_beforeToHtml();
	}
}
