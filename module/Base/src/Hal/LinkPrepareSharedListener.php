<?php

namespace Base\Hal;

use Zend\EventManager\EventInterface,
	Zend\EventManager\SharedEventManagerInterface,
	Zend\EventManager\SharedListenerAggregateInterface;
use Zend\Stdlib\CallbackHander;

use BjyAuthorize\Service\Authorize;
use ZF\Hal\Link\Link;

class LinkPrepareSharedListener implements SharedListenerAggregateInterface
{

	/**
	 * @var CallbackHander[]
	 */
	protected $listeners;

	/**
	 * @var Authorize
	 */
	protected $authService;

	/**
	 * @param Authorize $service
	 */
	public function __construct(Authorize $service)
	{
		$this->authService = $service;
	}

	/**
	 * @inheritDoc
	 */
	public function attachShared(SharedEventManagerInterface $ev)
	{
		$this->listeners[] = $ev->attach('ZF\Hal\Plugin\Hal', 'renderEntity', [$this, 'prepareEntity']);
	}

	/**
	 * @inheritDoc
	 */
	public function detachShared(SharedEventManagerInterface $ev)
	{
		foreach ($this->listeners as $listener) $ev->detach('ZF\Hal\Plugin\Hal', $listener);
	}

	/**
	 * @param EventInterface $e
	 */
	public function prepareEntity(EventInterface $e)
	{
		$this->links($e, $e->getParam('entity'));
	}

	/**
	 * @param EventInterface $e
	 * @param HalEntity $entity
	 */
	protected function links(EventInterface $e, $entity)
	{
		$source = $entity->entity;
		$links	= $entity->getLinks();

		if ($source instanceof LinkProvider) {
      		foreach ($source->provideLinks() as $options) {

        		if (isset($options['privilege'])) {
					if (is_bool($options['privilege'])) {
						$allowed = $options['privilege'];
					}
					else {
						$allowed = $this->authService->isAllowed($source, $options['privilege']);
					}
        		}
				else
					$allowed = true;

				$links->add(Link::factory(array_merge($options, ['props' => ['allowed' => $allowed]])));
      		}
    	}

		if ($source instanceof LinkPrepareAware) {
			$source->prepareLinks($links);
		}
	}
}
