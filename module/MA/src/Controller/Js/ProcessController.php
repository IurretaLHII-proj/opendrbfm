<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class ProcessController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return JsonViewModel
	 */
	public function detailAction()
	{
		return new HalJsonModel([
			'payload' => $this->prepareHalEntity($this->getEntity(), "process/detail/json"),
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$owner 	  = $this->params()->fromQuery('user');
		$customer = $this->params()->fromQuery('customer');
		$order 	  = $this->params()->fromRoute('order', 'created');
		$criteria = $this->params()->fromRoute('criteria', 'DESC');

		$collection = $em->getRepository("MA\Entity\Process")->getBy(
			$this->params()->fromQuery('title'),
			$this->params()->fromQuery('article'),
			$this->params()->fromQuery('machine'),
			$this->params()->fromQuery('line'),
			$this->params()->fromQuery('piece'),
			$this->params()->fromQuery('complexity'),
			$customer ? $em->getRepository("MA\Entity\Customer")->find($customer) : $customer,
			$owner ? $em->getRepository("MA\Entity\User")->find($owner) : $owner,
			$this->params()->fromQuery('order'),
			$this->params()->fromQuery('criteria')
		);

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'process/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function actionsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\AbstractProcessAction")
			->findBy(
				['process' => $this->getEntity()],
				['created' => 'DESC']
			);

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'process/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return JsonViewModel
	 */
	public function editAction()
	{
		$e	  = $this->getEntity();
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\ProcessForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$em->flush();

				$payload = ['payload' => $this->prepareHalEntity($e, "process/detail/json")];
			}
			else {
				$ex = new \ZF\ApiProblem\Exception\DomainException('Unprocessable entity', 422);
				$ex->setAdditionalDetails(['errors' => $form->getMessages()]);
				throw $ex;
			}
		}

		return new HalJsonModel($payload);
	}

	/**
	 * @return JsonViewModel
	 */
	public function versionAction()
	{
		$e    = new \MA\Entity\Version;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\VersionForm::class);

		$e->setProcess($this->getEntity());
		
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		//$form->get('operations')->setAllowRemove(false);
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();

				$payload = ['payload' => $this->prepareHalEntity($e, "process/version/detail/json")];
			}
			else {
				$ex = new \ZF\ApiProblem\Exception\DomainException('Unprocessable entity', 422);
				$ex->setAdditionalDetails(['errors' => $form->getMessages()]);
				throw $ex;
			}
		}

		return new HalJsonModel($payload);
	}
}
