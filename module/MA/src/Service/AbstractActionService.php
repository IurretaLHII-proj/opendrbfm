<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

abstract class AbstractActionService extends AbstractService
{
	/**
	 * @param EventInterface $e
	 */
	public function createAction(EventInterface $e)
	{
		switch ($e->getName()) {
			case self::EVENT_CREATE:
				return $this->newEntity($e);
			case self::EVENT_UPDATE:
				return $this->entityUpdated($e);
			default;
				return;
		}
	}

	/**
	 * @return string
	 */
	abstract protected function getClassName();

	/**
	 * @param EventInterface $e
	 */
	public function newEntity(EventInterface $e)
	{
		$process = $source = $e->getTarget();
		if (!$process instanceof \MA\Entity\ProcessInterface) {
			$process = $process->getProcess();
		}

		$actnNm = $this->getClassName();
		$action	= new $actnNm;
		$action->setProcess($process);
		$action->setSource($source);
		$action->setName($e->getName());
		$this->getEventManager()->trigger(self::EVENT_CREATE, $action);
		$this->getEntityManager()->persist($action);
	}

	/**
	 * @param EventInterface $e
	 */
	public function entityUpdated(EventInterface $e)
	{
		$process = $source = $e->getTarget();
		if (!$process instanceof \MA\Entity\ProcessInterface) {
			$process = $process->getProcess();
		}

		$em		   	= $this->getEntityManager();
		$uok 	   	= $em->getUnitOfWork();
		$meta      	= $em->getClassMetadata(get_class($source));

		$uok->computeChangeSet($meta, $source);
		$changeSet = $uok->getEntityChangeSet($source);
		//$uok->recomputeSingleEntityChangeSet($meta, $source);
		$changeSet = $this->relationsChangeSet($source, $changeSet);
		if ($changeSet === array()) {
			return;
		}

		$actnNm = $this->getClassName();
		$action	= new $actnNm;
		$action->setContent($changeSet);
		$action->setProcess($process);
		$action->setSource($source);
		$action->setName($e->getName());
		$this->getEventManager()->trigger(self::EVENT_CREATE, $action);
		$this->getEntityManager()->persist($action);
	}

	/**
	 * @param mixed $source
	 * @param array $changeSet
	 * @return array
	 */
	protected function relationsChangeSet($source, array $changeSet) {
		return $changeSet;
	}
}
