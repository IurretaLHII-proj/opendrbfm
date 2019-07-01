<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class OperationController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\Operation")->findBy([],['created' => 'DESC']);

		$paginator = $this->getPaginator($collection, 100);

		$payload = $this->prepareHalCollection($paginator, 'process/operation/json');

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
			->get(\MA\Form\OperationForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$em->persist($e);
				$em->flush();

				$payload = ['payload' => $this->prepareHalEntity($e, "process/operation/detail/json")];
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
		$e    = new \MA\Entity\HintType;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\HintTypeForm::class);

		$e->setOperation($this->getEntity());

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();
				$payload = ['payload' => $this->prepareHalEntity($e, "process/hint/type/detail")];
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
    public function stagesAction()
    {
		$e  = $this->getEntity();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$metadata = $this->hal()->getMetadataMap()->get("MA\Entity\Stage");
		$metadata->setHydrator(new \MA\Hydrator\Expanded\StageHydrator());
		$metadata->setMaxDepth(4);

		$p  = $this->params()->fromQuery('process');
		$m  = $this->params()->fromQuery('material');
		$t  = $this->params()->fromQuery('type');

		$collection = $em->getRepository("MA\Entity\Stage")->getBy(
			$p  ? $em->getRepository("MA\Entity\Process")->find($p) : $p,
			$e,
			$t  ? $em->getRepository("MA\Entity\VersionType")->find($t) : $t,
			$m  ? $em->getRepository("MA\Entity\Material")->find($m) : $m,
			$this->params()->fromQuery('state'),
			$this->params()->fromQuery('order', 'priority'),
			$this->params()->fromQuery('criteria', 'DESC')
		);

		//$collection = $e->getStages()->toArray();
		$paginator  = $this->getPaginator($collection);
		$payload = $this->prepareHalCollection($paginator, 'process/operation/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function hintsAction()
    {
		$e  = $this->getEntity();
		$em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		
		if ($this->params()->fromQuery('standard', false)) {
			$collection = $e->getHints()->filter(function($item) {return $item->isStandard();});
		}
		else {
			$collection = $e->getHints();
		}

		$paginator = $this->getPaginator($collection->toArray(), $collection->count());
		$payload = $this->prepareHalCollection($paginator, 'process/operation/detail/json');

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

	/**
	 * @return JsonViewModel
	 */
	public function replaceAction()
	{
		$e    = $this->getEntity();
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\OperationReplaceForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$o =$em->getRepository("MA\Entity\Operation")->find($form->get('operation')->getValue());

				foreach ($e->getStages() as $stage) {
					$e->removeStage($stage);
					$o->addStage($stage);
				}
				foreach ($e->getHints() as $hint) {
					$e->removeHint($hint);
					$o->addHint($hint);
				}

				return $this->deleteAction();
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
