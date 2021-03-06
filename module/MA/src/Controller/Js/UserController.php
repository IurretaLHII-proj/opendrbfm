<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class UserController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function actionsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\AbstractProcessAction")
			->findBy(
				['user' => $this->getEntity()],
				['created' => 'DESC']
			);

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'user/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function notificationsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\Notification")
			->findByUser($this->getEntity(), (bool) $this->params()->fromQuery('readed', false));

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'user/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\User")
			->findBy(
				[],
				['created' => 'DESC']
			);

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'user/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}
}
