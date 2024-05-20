<?php

namespace Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Convert\DataObject as ObjectConverter;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Magento\SalesRule\Model\RuleFactory;
use Magento\Store\Model\System\Store;

/**
 * Class General
 *
 * @package Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab
 */
class General extends Generic implements TabInterface {
	/**
	 * @var \Magento\Framework\Convert\DataObject
	 */
	protected $_objectConverter;

	/**
	 * @var \Magento\SalesRule\Model\RuleFactory
	 */
	protected $_salesRule;

	/**
	 * @var GroupRepositoryInterface
	 */
	protected $groupRepository;

	/**
	 * @var SearchCriteriaBuilder
	 */
	protected $_searchCriteriaBuilder;

    /**
     * @var \Magento\Store\Model\System\Store
     */
	protected $_systemStore;

	/**
	 * Constructor
	 *
	 * @param Context                  $context
	 * @param Registry                 $registry
	 * @param FormFactory              $formFactory
	 * @param RuleFactory              $salesRule
	 * @param ObjectConverter          $objectConverter
	 * @param GroupRepositoryInterface $groupRepository
	 * @param SearchCriteriaBuilder    $searchCriteriaBuilder
	 * @param array                    $data
	 */
	public function __construct(
		Context $context,
		Registry $registry,
		FormFactory $formFactory,
		RuleFactory $salesRule,
		ObjectConverter $objectConverter,
		GroupRepositoryInterface $groupRepository,
		SearchCriteriaBuilder $searchCriteriaBuilder,
        Store $systemStore,
		array $data = []
	)
	{
		$this->_objectConverter       = $objectConverter;
		$this->_salesRule             = $salesRule;
		$this->groupRepository        = $groupRepository;
		$this->_searchCriteriaBuilder = $searchCriteriaBuilder;
		$this->_systemStore = $systemStore;
		parent::__construct( $context, $registry, $formFactory, $data );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTabLabel()
	{
		return __( 'General' );
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTabTitle()
	{
		return __( 'General' );
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
	 * @return \Lof\ProductLabel\Block\Adminhtml\Label\Edit\Tab\Main
	 * @throws \Magento\Framework\Exception\LocalizedException
	 * @throws \Magento\Framework\Exception\NoSuchEntityException
	 */
	protected function _prepareForm()
	{
		$model = $this->_coreRegistry->registry( 'current_lofproductlabel_label' );
		/** @var \Magento\Framework\Data\Form $form */
		$form = $this->_formFactory->create();

		$fieldset = $form->addFieldset( 'general', [ 'legend' => __( 'General' ) ] );
		if ( $model->getId() ) {
			$fieldset->addField( 'entity_id', 'hidden', [ 'name' => 'entity_id' ] );
		}

		$fieldset->addField( 'open_tab_input',
			'hidden',
			[
				'name'               => 'open_tab_input',
				'after_element_html' => '<script>
                    require([
                      "jquery",
                      "Lof_ProductLabel/js/productlabel"
                    ], function ($) {
                       $("body").lofLabeltabs();
                    });
                 </script>',
			]
		);

		$fieldset->addField( 'name', 'text', [
			'name'     => 'name',
			'label'    => __( 'Name' ),
			'title'    => __( 'Name' ),
			'required' => true,
		] );

		$fieldset->addField( 'status', 'select', [
			'name'   => 'status',
			'label'  => __( 'Status' ),
			'title'  => __( 'Status' ),
			'values' => [
                1 => __( 'Active' ),
                0 => __( 'Inactive' ),
			],
		] );

		$validateClass = sprintf(
			'validate-not-negative-number validate-length maximum-length-%d',
			5
		);

		$fieldset->addField( 'priority', 'text', [
			'label' => __( 'Priority' ),
			'name'  => 'priority',
			'note'  => __( 'Used to specify the order, lower numbers correspond with earlier shown.' ),
			'class' => $validateClass,
		] );

		$fieldset->addField( 'exclusively', 'select', [
			'label'  => __( 'Exclusively' ),
			'note'   => __( 'Hide if there is a label with higher priority is already applied.' ),
			'name'   => 'exclusively',
			'values' => [
				0 => __( 'No' ),
				1 => __( 'Yes' ),
			],
		] );

		$fieldset->addField( 'use_for_parent', 'select', [
			'label'  => __( 'Use for Parent' ),
			'title'  => __( 'Use for Parent' ),
			'name'   => 'use_for_parent',
			'note'   => __( 'Display child\'s label for configurable parent products and grouped parent products.' ),
			'values' => [
				0 => __( 'No' ),
				1 => __( 'Yes' ),
			],
		] );

        if ( ! $this->_storeManager->isSingleStoreMode() ) {
            $field = $fieldset->addField(
                'stores',
                'multiselect',
                [
                    'label'    => __( 'Store' ),
                    'title'    => __( 'Store' ),
                    'values'   => $this->_systemStore->getStoreValuesForForm(),
                    'name'     => 'stores',
                    'required' => true,
                ]
            );

            $renderer = $this->getLayout()->createBlock(
                'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element'
            );

            $field->setRenderer( $renderer );
        } else {
            $fieldset->addField(
                'stores',
                'hidden',
                [ 'name' => 'stores', 'value' => $this->_storeManager->getStore( true )->getId() ]
            );
        }

		if ( ! $model->getId() ) {
			$model->setData( 'status', 1 );
		}

		$form->setValues( $model->getData() );
		$this->setForm( $form );

		return parent::_prepareForm();
	}
}
