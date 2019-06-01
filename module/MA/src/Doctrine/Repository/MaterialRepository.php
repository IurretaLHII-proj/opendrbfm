<?php

namespace MA\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Base\Entity\TeamInterface;
use Base\Entity\SeasonInterface;
use DateTime;

use MA\Entity\UserInterface;
use MA\Entity\CustomerInterface;
use MA\Entity\MaterialInterface;
use MA\Entity\VersionTypeInterface;
use MA\Entity\Material;

class MaterialRepository extends EntityRepository
{
	/**
	 * @return MaterialInterface[]
	 */
	public function findByName(
		$name = null, 
		$oBy, 
		$oCriteria
	) {
		$query = $this->createQueryBuilder('material');
    	$query->select('material')->orderBy("material.{$oBy}", $oCriteria);

		if ($name) $query->andWhere('material.text LIKE :name')->setParameter('name', "%{$name}%");

    	return $query->getQuery()->getResult();
	}
}
