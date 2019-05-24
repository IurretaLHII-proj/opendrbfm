<?php

namespace MA\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use MA\Entity\ProcessInterface;
use MA\Entity\VersionInterface;
use MA\Entity\HintTypeInterface;
use MA\Entity\HintInterface;
use DateTime;

class HintRepository extends EntityRepository
{
	/**
	 * @param HintTypeInterface $type 
	 * @return HintInterface[] 
	 */
	public function findByType(HintTypeInterface $type)
	{
		$result = [];
		$query = $this->createQueryBuilder('q');
    	$query->select('q');
    	//$query->select('q.number');
    	$query->andWhere('q.type = :type')->setParameter('type', $type);
    	//$query->groupBy('q.number');
    	//$query->orderBy('q.number', 'ASC');
    	return $query->getQuery()->getResult();
	}
}
