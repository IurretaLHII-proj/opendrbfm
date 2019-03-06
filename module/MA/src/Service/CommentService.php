<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\CommentProviderInterface;

class CommentService extends AbstractService
{
	const EVENT_REPLY = 'reply';

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
}
