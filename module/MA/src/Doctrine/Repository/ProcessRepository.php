<?php

namespace MA\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Base\Entity\TeamInterface;
use Base\Entity\SeasonInterface;
use DateTime;

class ProcessRepository extends EntityRepository
{
	/**
	 * @return ProcessInterface[]
	 */
	public function getBy($title = null, $oBy = "created", $oCriteria = "DESC") {
		$query = $this->createQueryBuilder('process');

    	$query->select('process')->orderBy("process.{$oBy}", $oCriteria);

		if ($title) $query->andWhere('process.title LIKE :n')->setParameter('n', "%{$title}%");

    	return $query->getQuery()->getResult();
	}
}
