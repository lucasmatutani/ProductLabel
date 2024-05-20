<?php

namespace Lof\ProductLabel\Model\DependencyInjection;

/**
 * Support for EE version.
 */
class Expected {
	/**
	 * @var \Magento\Framework\ObjectManagerInterface
	 */
	private $objectManagerInterface;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * Expected constructor.
	 *
	 * @param \Magento\Framework\ObjectManagerInterface $objectManagerInterface
	 * @param string                                    $name
	 */
	public function __construct(
		\Magento\Framework\ObjectManagerInterface $objectManagerInterface,
		$name = ''
	)
	{
		$this->objectManagerInterface = $objectManagerInterface;
		$this->name                   = $name;
	}

	/**
	 * @param $name
	 * @param $arguments
	 * @return bool|mixed
	 */
	public function __call( $name, $arguments )
	{
		$result = false;
		if ( $this->name && class_exists( $this->name ) ) {
			$object = $this->objectManagerInterface->create( $this->name );

			$result = call_user_func_array( [ $object, $name ], $arguments );
		}

		return $result;
	}
}
