<?php

namespace Lof\ProductLabel\Model\GiftCard\Model\Product;

/**
 * Class ReadHandler
 */
class ReadHandler extends \Lof\ProductLabel\Model\DependencyInjection\Expected
{
	/**
	 * ReadHandler constructor.
	 *
	 * @param \Magento\Framework\ObjectManagerInterface $objectManagerInterface
	 * @param string                                    $name
	 */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManagerInterface,
        $name = ''
    ) {
        parent::__construct($objectManagerInterface, \Magento\GiftCard\Model\Product\ReadHandler::class);
    }
}
