<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class ActionController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$r  = $em->getRepository("MA\Entity\AbstractProcessAction");


		$collection = $r->findBy([], ['created' => 'DESC']);
		$paginator = $this->getPaginator($collection);

		$payload = $this->prepareHalCollection($paginator, 'action/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}
}
