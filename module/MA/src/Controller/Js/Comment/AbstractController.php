<?php

namespace MA\Controller\Js\Comment;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

abstract class AbstractController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return string
	 */
	abstract protected function getClassName();

	/**
	 * @return JsonViewModel
	 */
	public function replyAction()
	{
		$className  = $this->getClassName();
		$e			= new $className;
		$em   		= $this->getEntityManager();
		$form 		= $this->getServiceLocator()
						->get('FormElementManager')
						->get(\MA\Form\CommentForm::class);

		$e->setParent($this->getEntity());
		$e->setSource($this->getEntity()->getSource());
		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\MA\Service\CommentService::EVENT_REPLY, $e);

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
}
