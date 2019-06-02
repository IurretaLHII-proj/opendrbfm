<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class VersionTypeController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\VersionType")->findByName(
			$this->params()->fromQuery('name'),
			$this->params()->fromQuery('order', 'created'),
			$this->params()->fromQuery('criteria', 'desc')
		);

		$paginator = $this->getPaginator($collection, count($collection));

		$payload = $this->prepareHalCollection($paginator, 'process/material/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function addAction()
    {
		$e    = new \MA\Entity\VersionType;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\VersionTypeForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$this->getEntityManager()->persist($e);
				$this->getEntityManager()->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/version/type/detail")
				];
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
	 * @return ViewModel
	 */
    public function editAction()
    {
		$em   = $this->getEntityManager();
		$e	  = $this->getEntity();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\VersionTypeForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$this->getEntityManager()->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/version/type/detail")
				];
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
	public function deleteAction()
	{
		$e	  = $this->getEntity();
		$em   = $this->getEntityManager();

		//$this->triggerService(\Base\Service\AbstractService::EVENT_DELETE, $e);

		$em->remove($e);
		$em->flush();

		return new HalJsonModel(['payload' => []]);
	}
}
