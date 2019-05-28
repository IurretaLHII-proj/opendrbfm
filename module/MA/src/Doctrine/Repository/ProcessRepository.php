<?php

namespace MA\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use Base\Entity\TeamInterface;
use Base\Entity\SeasonInterface;
use DateTime;

use MA\Entity\UserInterface;
use MA\Entity\CustomerInterface;

class ProcessRepository extends EntityRepository
{
	/**
	 * @return ProcessInterface[]
	 */
	public function getBy(
		$title = null, 
		$article = null, 
		$machine = null, 
		$line = null, 
		$piece = null, 
		$complexity = null, 
		CustomerInterface $customer = null, 
		UserInterface $owner = null, 
		$oBy = "created", 
		$oCriteria = "DESC"
	) {
		$query = $this->createQueryBuilder('process');
    	$query->select('process')->orderBy("process.{$oBy}", $oCriteria);

		if ($title) {
			$query->andWhere('process.number LIKE :number OR process.title LIKE :number')
				->setParameter('number', "%{$title}%");
		}

		if ($piece) {
			$query->andWhere('process.pieceName LIKE :piece OR process.pieceNumber LIKE :piece')
				->setParameter('piece', "%{$piece}%");
		}

		if ($article) {
			$query->andWhere('process.code LIKE :code')
				->setParameter('code', "%{$article}%");
		}

		if ($machine) {
			$query->andWhere('process.machine LIKE :machine')
				->setParameter('machine', "%{$machine}%");
		}

		if ($line) {
			$query->andWhere('process.line LIKE :line')
				->setParameter('line', "%{$line}%");
		}

		if ($complexity) {
			$query->andWhere('process.complexity = :complexity')
				->setParameter('complexity', $complexity);
		}

		if ($customer) {
			$query->andWhere('process.customer = :customer')
				->setParameter('customer', $customer);
		}

		if ($owner) {
			$query->andWhere('process.user = :owner')
				->setParameter('owner', $owner);
		}

    	return $query->getQuery()->getResult();
	}
}
