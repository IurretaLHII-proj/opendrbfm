<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class CommentService extends AbstractService
{
	const EVENT_REPLY = 'reply';

	public function increaseCommentCount(EventInterface $e) 
	{ 
		$e->getTarget()->getParent()->increaseCommentCount(); 
	}
}
