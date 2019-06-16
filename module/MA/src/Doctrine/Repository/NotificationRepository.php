<?php

namespace MA\Doctrine\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime;

class NotificationRepository extends EntityRepository
{
	/**
	 * @param UserInterface $user
	 * @param bool readed
	 */
	public function findByUser(\MA\Entity\UserInterface $user, $readed)
	{
		$query = $this->createQueryBuilder('n');

		return $query->select('n')
			->andWhere('n.subscriber = :subscriber')->setParameter('subscriber', $user)
			->andWhere('n.readed = :readed')->setParameter('readed', (bool) $readed)
			->innerJoin('n.action', 'a')
			->orderBy('a.created', 'desc')
			->getQuery()
			->getResult();
	
	}
}
