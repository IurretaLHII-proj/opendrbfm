<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;

class CommentService extends AbstractActionService
{
	/**
	 * @var string
	 */
	protected $__className__ = \MA\Entity\Action\Comment::class;

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}

	/**
	 * @param EventInterface $e
	 */
	public function createAction(EventInterface $e)
	{
		switch ($e->getName()) {
			case \MA\Service\CommentService::EVENT_REPLY:
				return $this->newEntity($e);
			default;
				return parent::createAction($e);
		}
	}
}
