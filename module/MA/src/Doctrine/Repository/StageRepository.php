<?php

namespace MA\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use MA\Entity\OperationInterface;
use MA\Entity\ProcessInterface;
use MA\Entity\MaterialInterface;
use MA\Entity\VersionTypeInterface;
use DateTime;

class StageRepository extends EntityRepository
{
	/**
	 * @return StageInterface[] 
	 */
	public function getBy(
		ProcessInterface $p = null, 
		OperationInterface $o = null, 
		VersionTypeInterface $vt = null, 
		MaterialInterface $m = null,
		$s = null,
		$order = 'created',
		$criteria = 'DESC'
	) {
	  	//return $this->_em->createQuery('SELECT u FROM MyDomain\Model\User u WHERE u.status = "admin"')
		//            ->getResult();

		$result = [];
		$query = $this->createQueryBuilder('stage');
    	$query->select('stage')
			->innerJoin('stage.operations', 'op')
			->innerJoin('stage.version', 'version')
			->innerJoin('version.process', 'process')
			;

		switch ($order) {
			case "stage":
				$order = "stage.order";
				break;
			case "process":
				$order = "process.title";
				break;
			default:
				$order = "stage.{$order}";
				break;
		}

		$query->orderBy($order, $criteria);

    	if ($p)  $query->andWhere('process = :p')->setParameter('p', $p);
		if ($vt) $query->andWhere('version.type = :vt')->setParameter('vt', $vt);
		if ($m)  $query->andWhere('version.material = :m')->setParameter('m', $m);
		if ($s !== null)  $query->andWhere('version.state = :s')->setParameter('s', $s);
		if ($o)  $query->andWhere('op = :o')->setParameter('o', $o);
		//$q = $query->getQuery()->getSQL();
    	//$query->groupBy('q.number');
    	//$query->orderBy('q.number', 'ASC');
    	return $query->getQuery()->getResult();
	}
}
