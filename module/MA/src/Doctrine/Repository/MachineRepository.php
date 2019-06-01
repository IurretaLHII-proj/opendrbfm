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

class MachineRepository extends EntityRepository
{
	/**
	 * @return MachineInterface[]
	 */
	public function findByName(
		$name = null, 
		$oBy, 
		$oCriteria
	) {
		$query = $this->createQueryBuilder('machine');
    	$query->select('machine')->orderBy("machine.{$oBy}", $oCriteria);

		if ($name) $query->andWhere('machine.name LIKE :name')->setParameter('name', "%{$name}%");

    	return $query->getQuery()->getResult();
	}
}
