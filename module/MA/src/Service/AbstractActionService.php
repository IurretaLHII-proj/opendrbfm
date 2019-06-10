<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;

abstract class AbstractActionService extends AbstractService
{
	/**
	 * @var array
	 */
	protected $changeSetFields = [];

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
	 * @var \ZF\Hal\Plugin\Hal
	 */
	protected $hal;

	public function getHal()
	{
		if ($this->hal === null) {
			$this->hal = $this->getServiceLocator()->get('ViewHelperManager')->get('hal');
		}

		return $this->hal;
	}

	/**
	 * @param mixed $source
	 * @return array
	 */
	protected function relationChangeDesc($source) 
	{
		$hal = $this->getHal();	
		$map = $hal->getMetadataMap();

		if (!$map->has($source)) {
			throw new \InvalidArgumentException(sprintf("%s entity not defined in hal metedataMap",
				get_class($source)));
		}

		$links = $hal->fromResource($hal->createEntityFromMetadata($source, $map->get($source)));

		return [
			'id'   => $source->getId(),
			'name' => (string) $source,
			'href' => $links['self']['href'],
		];
	}

	/**
	 * @param mixed $source
	 * @param array $changeSet
	 * @return array
	 */
	protected function relationsChangeSet($source, array $changeSet) {

		$class = $this->getEntityManager()->getClassMetadata(get_class($source));

		foreach ($class->associationMappings as $field => $assoc) {
			if (!in_array($field, $this->changeSetFields)) {
				unset($changeSet[$field]);
				continue;
			}
			$diff = [];
			if ($class->isCollectionValuedAssociation($field)) {
				$coll = $class->reflFields[$field]->getValue($source);
				foreach ($coll->getInsertDiff() as $entity) {
					if (!isset($diff['insert'])) $diff['insert'] = [];
					$diff['insert'][] = $this->relationChangeDesc($entity);
				}
				foreach ($coll->getDeleteDiff() as $entity) {
					if (!isset($diff['delete'])) $diff['delete'] = [];
					$diff['delete'][] = $this->relationChangeDesc($entity);
				}
				$changeSet[$field] = $diff;
			}
			elseif (array_key_exists($field, $changeSet)) {
				foreach ($changeSet[$field] as $i => $source) {
					$diff[] = $source ? $this->relationChangeDesc($source) : null;
				};
				$changeSet[$field] = $diff;
			}
		}
		return $changeSet;
	}
}
