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
use MA\Entity\Machine;

class VersionTypeRepository extends EntityRepository
{
	/**
	 * @return MachineInterface[]
	 */
	public function findByName(
		$name = null, 
		$oBy, 
		$oCriteria
	) {
		$query = $this->createQueryBuilder('type');
    	$query->select('type')->orderBy("type.{$oBy}", $oCriteria);

		if ($name) $query->andWhere('type.name LIKE :name')->setParameter('name', "%{$name}%");

    	return $query->getQuery()->getResult();
	}
}
