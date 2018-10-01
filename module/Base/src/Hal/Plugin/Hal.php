<?php

namespace Base\Hal\Plugin;

class Hal extends \ZF\Hal\Plugin\Hal
{
	/**
	 * @return Hal
	 */
	public function __invoke()
	{
		return $this;
	}
}
