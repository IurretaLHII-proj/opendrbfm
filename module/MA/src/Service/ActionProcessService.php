<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;

class ProcessService extends AbstractActionService
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

		$uok = $this->getEntityManager()->getUnitOfWork();
		//FIXME
		if ($e->getName() === self::EVENT_UPDATE) {

			$uok->computeChangeSets();
			$changeSet = $uok->getEntityChangeSet($source);

			if ($source instanceof \MA\Entity\StageInterface) {
				foreach ($source->getImages()->getInsertDiff() as $image) {
					if (!isset($diff['insert'])) $diff['insert'] = [];
					$diff['insert'][] = $image->jsonSerialize();
				}

				foreach ($source->getImages()->getDeleteDiff() as $image) {
					if (!isset($diff['delete'])) $diff['delete'] = [];
					$diff['delete'][] = $image->jsonSerialize();
				}

				if (isset($diff)) {
					$changeSet['images'] = $diff;
					unset($diff);
				}

				foreach ($source->getOperations()->getInsertDiff() as $operation) {
					if (!isset($diff['insert'])) $diff['insert'] = [];
					$diff['insert'][] = $operation->jsonSerialize();
				}

				foreach ($source->getOperations()->getDeleteDiff() as $operation) {
					if (!isset($diff['delete'])) $diff['delete'] = [];
					$diff['delete'][] = $operation->jsonSerialize();
				}

				if (isset($diff)) {
					$changeSet['operations'] = $diff;
					unset($diff);
				}
			}
			elseif ($source instanceof \MA\Entity\HintInterface) {
/*
				foreach ($source->getParents()->getInsertDiff() as $parent) {
					if (!isset($diff['insert'])) $diff['insert'] = [];
					$diff['insert'][] = $parent->jsonSerialize();
				}

				foreach ($source->getParents()->getDeleteDiff() as $parent) {
					if (!isset($diff['delete'])) $diff['delete'] = [];
					$diff['delete'][] = $parent->jsonSerialize();
				}

				if (isset($diff)) {
					$changeSet['parents'] = $diff;
					unset($diff);
				}
				$nav = $this->sm->get(\Base\Controller\Service\Navigation::class);
				foreach ($source->getReasons()->getInsertDiff() as $reason) {
					$nav->triggerService(self::EVENT_CREATE, $reason); //Insert owner
					//$reason->setText('ccc');
					if (!isset($diff['insert'])) $diff['insert'] = [];
					$diff['insert'][] = $reason->jsonSerialize();
					$metaData = $this->getEntityManager()
						->getClassMetadata(get_class($reason));
					    $uok->recomputeSingleEntityChangeSet($metaData, $reason);
				}

				foreach ($source->getReasons()->getDeleteDiff() as $reason) {
					if (!isset($diff['delete'])) $diff['delete'] = [];
					$diff['delete'][] = $reason->jsonSerialize();
				}

				if (isset($diff)) {
					$changeSet['reasons'] = $diff;
					unset($diff);
				}

				foreach ($source->getSuggestions()->getInsertDiff() as $suggestion) {
					$nav->triggerService(self::EVENT_CREATE, $suggestion); //Insert owner
					if (!isset($diff['insert'])) $diff['insert'] = [];
					$diff['insert'][] = $suggestion->jsonSerialize();
					$metaData = $this->getEntityManager()
						->getClassMetadata(get_class($suggestion));
					    $uok->recomputeSingleEntityChangeSet($metaData, $suggestion);
				}

				foreach ($source->getSuggestions()->getDeleteDiff() as $suggestion) {
					if (!isset($diff['delete'])) $diff['delete'] = [];
					$diff['delete'][] = $suggestion->jsonSerialize();
				}

				if (isset($diff)) {
					$changeSet['suggestions'] = $diff;
					unset($diff);
				}

				foreach ($source->getInfluences()->getInsertDiff() as $influence) {
					$nav->triggerService(self::EVENT_CREATE, $influence); //Insert owner
					if (!isset($diff['insert'])) $diff['insert'] = [];
					$diff['insert'][] = $influence->jsonSerialize();
					$metaData = $this->getEntityManager()
						->getClassMetadata(get_class($influence));
					    $uok->recomputeSingleEntityChangeSet($metaData, $influence);
				}

				foreach ($source->getInfluences()->getDeleteDiff() as $influence) {
					if (!isset($diff['delete'])) $diff['delete'] = [];
					$diff['delete'][] = $influence->jsonSerialize();
				}

				if (isset($diff)) {
					$changeSet['influences'] = $diff;
					unset($diff);
				}
 */
			}

			if ($changeSet === array()) {
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
