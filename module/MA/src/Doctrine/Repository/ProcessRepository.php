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

class ProcessRepository extends EntityRepository
{
	/**
	 * @return ProcessInterface[]
	 */
	public function getBy(
		$tpl,
		$title = null, 
		$article = null, 
		$line = null, 
		$piece = null, 
		$complexity = null, 
		CustomerInterface $customer = null, 
		UserInterface $owner = null, 
		ProductivePlant $plant = null,
		Machine $machine = null,
		MaterialInterface $material = null,
		VersionTypeInterface $type = null,
		$state = null,
		$oBy = "created", 
		$oCriteria = "DESC"
	) {
		$query = $this->createQueryBuilder('process');
		$query->select('process')
			->andWhere('process.tpl = :tpl')->setParameter('tpl', (bool) $tpl)
			->orderBy("process.{$oBy}", $oCriteria);

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

		if ($plant) {
			$query->andWhere('process.plant = :plant')
				->setParameter('plant', $plant);
		}

		if ($machine) {
			$query->andWhere('process.machine = :machine')
				->setParameter('machine', $machine);
		}

		if (!($material === null && $type === null && $state === null)) {
			$query->innerJoin('process.versions', 'version');

			if ($material) {
				$query->andWhere('version.material = :material')
					->setParameter('material', $material);
			}
			if ($type) {
				$query->andWhere('version.type = :type')
					->setParameter('type', $type);
			}
			if ($state) {
				$query->andWhere('version.state = :state')
					->setParameter('state', $state);
			}
		}

    	return $query->getQuery()->getResult();
	}
}
