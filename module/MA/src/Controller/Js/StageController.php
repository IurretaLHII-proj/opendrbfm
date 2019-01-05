<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class StageController extends \Base\Controller\Js\AbstractActionController
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

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

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

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

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

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $img);

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

	/**
	 * @return ViewModel
	 */
    public function childrenAction()
    {
		$e  = $this->getEntity();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$children = [];
		while (!$e->getChildren()->isEmpty()) {
			$children[] = $e->getChildren()->first();
			$e = $e->getChildren()->first();
		}

		$paginator = $this->getPaginator($children, count($children));
		$payload = $this->prepareHalCollection($paginator, 'process/stage/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function _cloneAction($entity)
    {
		$c = clone $entity;

		foreach ($entity->getImages() as $img) {
			$c->addImage(clone $img);
		}

		foreach ($entity->getHints() as $hint) {
			$_hint = clone $hint;
			foreach ($hint->getSimulations() as $sm) {
				$_sm = clone $sm;
				foreach ($sm->getReasons() as $r) 		$_sm->addReason(clone $r);
				foreach ($sm->getInfluences() as $r) 	$_sm->addInfluence(clone $r);
				foreach ($sm->getSuggestions() as $r) 	$_sm->addSuggestion(clone $r);
				$_hint->addSimulation($_sm);
			}
			$c->addHint($_hint);
		}

		foreach ($entity->getChildren() as $child) {
			$c->addChild($this->_cloneAction($child));
		}

		return $c;
	}

	/**
	 * @return ViewModel
	 */
    public function cloneAction($entity = null)
    {
		$c = $this->_cloneAction($this->getEntity());

		$this->triggerService(\MA\Service\StageService::EVENT_CLONE, $c);

		$this->getEntityManager()->persist($c);
		$this->getEntityManager()->flush();

		return new HalJsonModel([
			'payload' => $this->prepareHalEntity($c, "process/stage/detail/json")
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function hintsAction()
    {
		$e  = $this->getEntity();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$paginator = $this->getPaginator($e->getHints()->toArray(), $e->getHints()->count());
		$payload = $this->prepareHalCollection($paginator, 'process/stage/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
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
