<?php

namespace MA\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use MA\Entity\OperationType;
use MA\Entity\OperationInterface;
use MA\Entity\ProcessInterface;
use MA\Entity\MaterialInterface;
use MA\Entity\VersionTypeInterface;
use MA\Entity\HintTypeInterface;
use MA\Entity\HintInterface;
use DateTime;

class HintRepository extends EntityRepository
{
	/**
	 * @param HintTypeInterface $t
	 * @return HintInterface[] 
	 */
	public function findByType(
		ProcessInterface $p = null, 
		OperationType $ot = null, 
		OperationInterface $o = null, 
		HintTypeInterface $t = null, 
		VersionTypeInterface $vt = null, 
		MaterialInterface $m = null,
		$s = null,
		$prior = 0,
		$order = 'priority',
		$criteria = 'DESC'
	) {
		$result = [];
		$query = $this->createQueryBuilder('hint');
    	$query->select('hint')
			->innerJoin('hint.type', 'type')
			->innerJoin('type.operation', 'operation')
			->innerJoin('hint.stage', 'stage')
			->innerJoin('stage.version', 'version')
			->innerJoin('version.process', 'process')
			->andWhere('hint.priority > :prior')->setParameter('prior', $prior)
			->andWhere('process.tpl = false')
			;

		switch ($order) {
			case "name":
				$order = "type.{$order}";
				break;
			case "stage":
				$order = "stage.order";
				break;
			case "name":
				$order = "type.{$order}";
				break;
			default:
				$order = "hint.{$order}";
				break;
		}

		$query->orderBy($order, $criteria);

    	if ($p)  $query->andWhere('process = :p')->setParameter('p', $p);
    	if ($t)  $query->andWhere('hint.type = :t')->setParameter('t', $t);
		if ($vt) $query->andWhere('version.type = :vt')->setParameter('vt', $vt);
		if ($m)  $query->andWhere('version.material = :m')->setParameter('m', $m);
		if ($s)  $query->andWhere('version.state = :s')->setParameter('s', $s);
		if ($o)  $query->andWhere('operation = :o')->setParameter('o', $o);
		if ($ot) $query->andWhere('operation.type = :ot')->setParameter('ot', $ot);
		//$q = $query->getQuery()->getSQL();
    	//$query->groupBy('q.number');
    	//$query->orderBy('q.number', 'ASC');
    	return $query->getQuery()->getResult();
	}
}
