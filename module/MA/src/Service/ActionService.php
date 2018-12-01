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
		$diff   = [];
		$source = $e->getTarget();

		switch (true) {
			case $source instanceof \MA\Entity\OperationInterface:
				$action = new \MA\Entity\Action\Operation; break;
			default:
				throw new \RuntimeException(sprintf("Action class for %s source type not especified", 
					get_class($source)));
		}

		$em  = $this->getEntityManager();
		//$uok = $this->getEntityManager()->getUnitOfWork();

		foreach ($source->getChildren() as $op) {
			//	var_dump($op->getText(),$e->getName());
			if (!$em->contains($op)) {
				$this->getNavService()->triggerService(self::EVENT_CREATE, $op);
			}
			if (!isset($diff['insert'])) $diff['insert'] = [];
			$diff['insert'][] = $op->jsonSerialize();
		}

		$action->setContent($diff);
		$action->setSource($source);
		$action->setName($e->getName());
		$this->getEventManager()->trigger(self::EVENT_CREATE, $action);
		$this->getEntityManager()->persist($action);
	}

	/**
	 * @param EventInterface $e
	 */
	public function _createAction(EventInterface $e)
	{
		$source = $e->getTarget();

		switch (true) {
			case $source instanceof \MA\Entity\OperationInterface:
				$action = new \MA\Entity\Action\Operation; break;
			default:
				throw new \RuntimeException(sprintf("Action class for %s source type not especified", 
					get_class($source)));
		}

		$uok = $this->getEntityManager()->getUnitOfWork();

		$children = $source->getChildren() instanceof \Doctrine\ORM\PersistentCollection ? 
			$source->getChildren()->getInsertDiff() : $source->getChildren();

			if ($e->getName() === self::EVENT_UPDATE) {
			$metaData = $this->getEntityManager()
				->getClassMetadata(get_class($source));
			$uok->recomputeSingleEntityChangeSet($metaData, $source);
			}


		foreach ($children as $op) {
			//	var_dump($op->getText(),$e->getName());
			//if (!$op->getId()) {
			$this->getNavService()->triggerService(self::EVENT_CREATE, $op);
			//}
			if (!isset($diff['insert'])) $diff['insert'] = [];
			$diff['insert'][] = $op->jsonSerialize();
		}

			if ($e->getName() === self::EVENT_UPDATE) {
			$metaData = $this->getEntityManager()
				->getClassMetadata(get_class($source));
			$uok->recomputeSingleEntityChangeSet($metaData, $source);
			}

		$action->setContent([]);
		$action->setSource($source);
		$action->setName($e->getName());
		$this->getEventManager()->trigger(self::EVENT_CREATE, $action);
		$this->getEntityManager()->persist($action);
	}
}
