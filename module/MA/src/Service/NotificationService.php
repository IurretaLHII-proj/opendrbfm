<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class NotificationService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createNotifications(EventInterface $e)
	{
		$action  = $e->getTarget();
		$comment = $action->getSource();

		foreach ($comment->getSuscribers() as $subscriber) {
			$notification = new \MA\Entity\Notification;
			$notification->setSubscriber($subscriber);
			$notification->setAction($action);
			$this->getEntityManager()->persist($notification);
		}
	}
}
