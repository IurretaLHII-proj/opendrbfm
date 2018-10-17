<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

class ActionService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createAction(EventInterface $e)
	{
		$process = $source = $e->getTarget();

		switch (true) {
			case $source instanceof \MA\Entity\ProcessInterface:
				$action = new \MA\Entity\Action\Process; break;
			case $source instanceof \MA\Entity\StageInterface:
				$action = new \MA\Entity\Action\Stage; break;
			case $source instanceof \MA\Entity\HintInterface:
				$action = new \MA\Entity\Action\Hint; break;
			default:
				throw new \RuntimeException(sprintf("Action class for %s source type not especified", 
					get_class($source)));
		}

		if (!$process instanceof \MA\Entity\ProcessInterface) {
			$process = $process->getProcess();
		}

		if ($e->getName() === self::EVENT_UPDATE) {
			$uok = $this->getEntityManager()->getUnitOfWork();
			$uok->computeChangeSets();
			if (array() === ($changeSet = $uok->getEntityChangeSet($source))) {
				return;
			}

			$action->setContent($changeSet);
		}

		$action->setProcess($process);
		$action->setSource($source);
		$action->setName($e->getName());
		$this->getEventManager()->trigger(self::EVENT_CREATE, $action);
		$this->getEntityManager()->persist($action);
	}
}
