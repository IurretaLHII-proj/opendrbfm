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
use MA\Entity\ProductivePlant;
use MA\Entity\Machine;

class PlantRepository extends EntityRepository
{
	/**
	 * @return ProdiuctivePlantInterface[]
	 */
	public function findByName(
		$name = null, 
		$oBy, 
		$oCriteria
	) {
		$query = $this->createQueryBuilder('plant');
    	$query->select('plant')->orderBy("plant.{$oBy}", $oCriteria);

		if ($name) $query->andWhere('plant.name LIKE :name')->setParameter('name', "%{$name}%");

    	return $query->getQuery()->getResult();
	}
}
