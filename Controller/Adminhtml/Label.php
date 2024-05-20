<?php

namespace Lof\ProductLabel\Controller\Adminhtml;

use Lof\ProductLabel\Api\LabelRepositoryInterface;

/**
 * Class Label
 *
 * @package Lof\ProductLabel\Controller\Adminhtml
 */
abstract class Label extends \Magento\Backend\App\Action {
	/**
	 * Core registry
	 *
	 * @var \Magento\Framework\Registry
	 */
	protected $coreRegistry;

	/**
	 * @var \Magento\Framework\View\Result\PageFactory
	 */
	protected $resultPageFactory;

	/**
	 * File system
	 *
	 * @var \Magento\Framework\Filesystem
	 */
	protected $filesystem;

	/**
	 * File Uploader factory
	 *
	 * @var \Magento\MediaStorage\Model\File\UploaderFactory
	 */
	protected $fileUploaderFactory;

	/**
	 * File check
	 *
	 * @var \Magento\Framework\Filesystem\Io\File
	 */
	protected $ioFile;

	/**
	 * @var \Lof\ProductLabel\Helper\Shape
	 */
	protected $shapeHelper;

	/**
	 * @var \Psr\Log\LoggerInterface
	 */
	protected $logger;

	/**
	 * @var \Lof\ProductLabel\Model\Serializer
	 */
	protected $serializer;

	/**
	 * @var \Lof\ProductLabel\Model\RuleFactory
	 */
	protected $ruleFactory;

	/**
	 * @var \Lof\ProductLabel\Model\Indexer\LabelIndexer
	 */
	protected $labelIndexer;

	/**
	 * @var \Lof\ProductLabel\Helper\Config
	 */
	protected $config;

	/**
	 * @var LabelRepositoryInterface
	 */
	protected $labelRepository;

	/**
	 * @var \Magento\Framework\Escaper
	 */
	protected $escaper;

	/**
	 * Label constructor.
	 *
	 * @param \Magento\Backend\App\Action\Context              $context
	 * @param \Magento\Framework\Registry                      $coreRegistry
	 * @param \Magento\Framework\View\Result\PageFactory       $resultPageFactory
	 * @param \Magento\Framework\Filesystem                    $filesystem
	 * @param \Magento\Framework\Filesystem\Io\File            $ioFile
	 * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
	 * @param \Lof\ProductLabel\Helper\Shape                   $shapeHelper
	 * @param \Psr\Log\LoggerInterface                         $logger
	 * @param \Lof\ProductLabel\Model\Serializer               $serializer
	 * @param \Lof\ProductLabel\Model\RuleFactory              $ruleFactory
	 * @param \Lof\ProductLabel\Model\Indexer\LabelIndexer     $labelIndexer
	 * @param \Lof\ProductLabel\Helper\Config                  $config
	 * @param \Lof\ProductLabel\Api\LabelRepositoryInterface   $labelRepository
	 * @param \Magento\Framework\Escaper                       $escaper
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\Registry $coreRegistry,
		\Magento\Framework\View\Result\PageFactory $resultPageFactory,
		\Magento\Framework\Filesystem $filesystem,
		\Magento\Framework\Filesystem\Io\File $ioFile,
		\Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
		\Lof\ProductLabel\Helper\Shape $shapeHelper,
		\Psr\Log\LoggerInterface $logger,
		\Lof\ProductLabel\Model\Serializer $serializer,
		\Lof\ProductLabel\Model\RuleFactory $ruleFactory,
		\Lof\ProductLabel\Model\Indexer\LabelIndexer $labelIndexer,
		\Lof\ProductLabel\Helper\Config $config,
		LabelRepositoryInterface $labelRepository,
		\Magento\Framework\Escaper $escaper
	)
	{
		$this->coreRegistry = $coreRegistry;
		parent::__construct( $context );
		$this->resultPageFactory   = $resultPageFactory;
		$this->filesystem          = $filesystem;
		$this->ioFile              = $ioFile;
		$this->fileUploaderFactory = $fileUploaderFactory;
		$this->shapeHelper         = $shapeHelper;
		$this->logger              = $logger;
		$this->serializer          = $serializer;
		$this->ruleFactory         = $ruleFactory;
		$this->labelIndexer        = $labelIndexer;
		$this->config              = $config;
		$this->labelRepository     = $labelRepository;
		$this->escaper             = $escaper;
	}

	/**
	 * Initiate action
	 *
	 * @return $this
	 */
	protected function _initAction()
	{
		$this->_view->loadLayout();
		$this->_setActiveMenu( 'Lof_ProductLabel::label' )->_addBreadcrumb( __( 'Product Labels' ), __( 'Product Labels' ) );

		return $this;
	}

	/**
	 * Determine if authorized to perform group actions.
	 *
	 * @return bool
	 */
	protected function _isAllowed()
	{
		return $this->_authorization->isAllowed( 'Lof_ProductLabel::label' ) && $this->config->isEnabled();
	}
}
