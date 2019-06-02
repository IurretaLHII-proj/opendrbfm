<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class HintController extends \Base\Controller\Js\AbstractActionController
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
			->get(\MA\Form\HintForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$em->flush();

				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/hint/detail/json"),
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
		$e	  = new \MA\Entity\HintReason;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\HintReasonForm::class);

		$e->setHint($this->getEntity());
		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$data = Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY);
			$form->setData($data);
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);
				//die('a');

				$em->persist($e);
				$em->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/hint/reason/detail/json")
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
	 * @return ViewModel
	 */
    public function commentsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\comment\Hint")
			->findBy(
				['source'  => $this->getEntity(), 'parent' => null],
				['created' => 'DESC']
			);

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'process/hint/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return JsonViewModel
	 */
	public function commentAction()
	{
		$e	  = new \MA\Entity\Comment\Hint;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\CommentForm::class);

		$e->setHint($this->getEntity());
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
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$r  = $em->getRepository("MA\Entity\Hint");

		$p  = $this->params()->fromQuery('process');
		$m  = $this->params()->fromQuery('material');
		$t  = $this->params()->fromQuery('type');
		$ot = $this->params()->fromQuery('opType');
		$o  = $this->params()->fromQuery('op');
		$h  = $this->params()->fromQuery('hint');

		$collection = $r->findByType(
			$p  ? $em->getRepository("MA\Entity\Process")->find($p) : $p,
			$ot ? $em->getRepository("MA\Entity\OperationType")->find($ot) : $ot,
			$o  ? $em->getRepository("MA\Entity\Operation")->find($o) : $o,
			$h  ? $em->getRepository("MA\Entity\HintType")->find($h) : $h,
			$t  ? $em->getRepository("MA\Entity\VersionType")->find($t) : $t,
			$m  ? $em->getRepository("MA\Entity\Material")->find($m) : $m,
			$this->params()->fromQuery('state'),
			$this->params()->fromQuery('prior', 0),
			$this->params()->fromQuery('order', 'priority'),
			$this->params()->fromQuery('criteria', 'DESC')
		);

		$paginator = $this->getPaginator($collection);

		$metadata = $this->hal()->getMetadataMap()->get("MA\Entity\Hint");
		$metadata->setHydrator(new \MA\Hydrator\Expanded\HintHydrator());
		$metadata->setMaxDepth(4);

		$payload = $this->prepareHalCollection($paginator, 'process/hint/json', [], [
			//'query' => $this->params()->fromQuery(), 
		]);

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}
}
