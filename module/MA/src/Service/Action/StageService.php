<?php

namespace MA\Service\Action;

use Zend\EventManager\EventInterface;
use MA\Service\AbstractActionService;

class StageService extends AbstractActionService
{
	/**
	 * @var string
	 */
	protected $__className__ = \MA\Entity\Action\Stage::class;

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}

	/**
	 * @inheritDoc
	 */
	protected function relationsChangeSet($source, array $changeSet) 
	{
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
		return $changeSet;
	/*
		elseif ($source instanceof \MA\Entity\HintInterface) {
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
}
