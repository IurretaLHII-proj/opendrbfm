<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\CommentProviderInterface;

class CommentService extends AbstractService
{
	const EVENT_REPLY = 'reply';

	/**
	 * @param EventInterface $e
	 */
	public function increaseCommentCount(EventInterface $e) 
	{ 
		$target = $e->getName() == self::EVENT_REPLY ? 
			$e->getTarget()->getParent() : $e->getTarget()->getSource();

		if (!$target instanceof CommentProviderInterface) {
			throw new \RuntimeException(sprintf("%s expected, %s received", 
				CommentProviderInterface::class, get_class($target)));
		}

		$target->increaseCommentCount(); 
	}

	/**
	 * @param EventInterface $e
	 */
	public function decreaseCommentCount(EventInterface $e) 
	{ 
		$target = $e->getTarget()->hasParent() ? 
			$e->getTarget()->getParent() : $e->getTarget()->getSource();

		if (!$target instanceof CommentProviderInterface) {
			throw new \RuntimeException(sprintf("%s expected, %s received", 
				CommentProviderInterface::class, get_class($target)));
		}

		$target->decreaseCommentCount(); 
	}
}
