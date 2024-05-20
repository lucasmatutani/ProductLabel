<?php

namespace Lof\ProductLabel\Model;

/**
 * Class Serializer
 *
 * @package Lof\ProductLabel\Model
 */
class Serializer {
	/**
	 * @var null|\Magento\Framework\Serialize\SerializerInterface
	 */
	private $serializer;

	/**
	 * @var \Magento\Framework\Unserialize\Unserialize
	 */
	private $unserialize;

	public function __construct(
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Magento\Framework\Unserialize\Unserialize $unserialize
	)
	{
		if ( interface_exists( \Magento\Framework\Serialize\SerializerInterface::class ) ) {
			$this->serializer = $objectManager->get( \Magento\Framework\Serialize\SerializerInterface::class );
		}

		$this->unserialize = $unserialize;
	}

	/**
	 * @param $value
	 * @return bool|string
	 */
	public function serialize( $value )
	{
		if ( $this->serializer === null ) {
			return serialize( $value );
		}

		return $this->serializer->serialize( $value );
	}

	/**
	 * @param $value
	 * @return array|bool|float|int|mixed|string|null
	 */
	public function unserialize( $value )
	{
		if ( $this->serializer === null ) {
			return $this->unserialize->unserialize( $value );
		}

		try {
			return $this->serializer->unserialize( $value );
		} catch ( \InvalidArgumentException $exception ) {
			return unserialize( $value );
		}
	}
}
