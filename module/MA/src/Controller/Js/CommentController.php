<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class CommentController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function commentsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\comment\Hint")
			->findBy(
				['parent'  => $this->getEntity()],
				['created' => 'DESC']
			);

		$paginator = $this->getPaginator($collection);

		return new HalJsonModel([
			'payload' => $this->prepareHalCollection($paginator, 'process/comment/detail/json'),
		]);
	}

	/**
	 * @return JsonViewModel
	 */
	public function replyAction()
	{
		$e	  = new \MA\Entity\Comment\Hint;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\CommentForm::class);

		$e->setParent($this->getEntity());
		$e->setHint($this->getEntity()->getHint());
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
