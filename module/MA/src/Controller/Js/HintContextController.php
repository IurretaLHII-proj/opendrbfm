<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class HintContextController extends \Base\Controller\Js\AbstractActionController
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
			->get(\MA\Form\HintContextForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$em->flush();

				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/hint/context/detail/json"),
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

	/**
	 * @return JsonViewModel
	 */
	public function simulateAction()
	{
		$e	  = new \MA\Entity\Simulation;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\HintRenderForm::class);

		$e->setContext($this->getEntity());
		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$data = Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY);
			$validationGroup = isset($data['state']) && $data['state'] > \MA\Entity\Simulation::STATE_CREATED ? \Zend\Form\FormInterface::VALIDATE_ALL : ['state', 'suggestions', 'effects', 'preventions']; 
			$form->setValidationGroup($validationGroup);
			$form->setData($data);
			if ($form->isValid()) {
				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/hint/simulation/detail/json")
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
	public function relationAction()
	{
		$e	  = new \MA\Entity\HintRelation;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\HintRelationForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$data = Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY);
			$form->setData($data);
			if ($form->isValid()) {
				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/hint/relation/detail/json")
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
	public function reasonAction()
	{
		return $this->noteAction(new \MA\Entity\Note\ContextReason);
	}

	/**
	 * @return JsonViewModel
	 */
	public function influenceAction()
	{
		return $this->noteAction(new \MA\Entity\Note\ContextInfluence);
	}

	/**
	 * @param AbstractNote $e
	 * @return JsonViewModel
	 */
	protected function noteAction(\MA\Entity\AbstractNote $e)
	{
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\NoteForm::class);

		$e->setContext($this->getEntity());
		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/note/detail/json")
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
	public function commentAction()
	{
		$e	  = new \MA\Entity\Comment\HintContext;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\CommentForm::class);

		$e->setContext($this->getEntity());
		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/comment/detail/json")
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
    public function commentsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$paginator = $this->getPaginator($em->getRepository("MA\Entity\Comment\HintContext")
			->findBy(
				['source'  => $this->getEntity(), 'parent' => null],
				['created' => 'DESC']
			));

		$payload = $this->prepareHalCollection($paginator, 'process/hint/context/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}
}
