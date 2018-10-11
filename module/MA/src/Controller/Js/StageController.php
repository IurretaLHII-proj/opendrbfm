<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class StageController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return JsonViewModel
	 */
	public function editAction()
	{
		$e	  = $this->getEntity();
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\StageForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				//$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $img);

				$em->persist($e);
				$em->flush();

				$payload = ['payload' => $this->prepareHalEntity($e, "process/stage/detail/json")];
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
	public function hintAction()
	{
		$e    = new \MA\Entity\Hint;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\HintForm::class);

		$e->setStage($this->getEntity());

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				//$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $img);

				$em->persist($e);
				$em->flush();
				$payload = ['payload' => $this->prepareHalEntity($e, "process/hint/detail/json")];
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
	public function imageAction()
	{
		$img  = new \MA\Entity\Image\IStage;

		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')->get(\Image\Form\ImageUploadForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new \Image\Hydrator\ImageHydrator);
		$form->bind($img);

		if ($this->getRequest()->isPost()) {
			$form->setData($this->params()->fromFiles());
			if ($form->isValid()) {

				//$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $img);

				$em->persist($img);
				$em->flush();

				$payload = ['payload' => $this->prepareHalEntity($img, "image/detail", $img->getId())];
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
