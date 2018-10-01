<?php

namespace Base\Hal;

use ZF\Hal\Link\LinkCollection;

interface LinkPrepareAware
{
	/**
	 * @param LinkCollection $links
	 * @return void
	 */
	public function prepareLinks(LinkCollection $links);
}
