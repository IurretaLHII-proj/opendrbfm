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

		$collection = $em->getRepository("MA\Entity\AbstractComment")
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
	 * FIXME
	 * @return JsonViewModel
	 */
	public function replyAction()
	{
		switch (true) {
			case $this->entity instanceof \MA\Entity\Comment\Version:
				$forward = Comment\VersionController::class;
				break;

			case $this->entity instanceof \MA\Entity\Comment\Stage:
				$forward = Comment\StageController::class;
				break;

			case $this->entity instanceof \MA\Entity\Comment\Hint:
				$forward = Comment\HintController::class;
				break;

			case $this->entity instanceof \MA\Entity\Comment\HintReason:
				$forward = Comment\HintReasonController::class;
				break;

			case $this->entity instanceof \MA\Entity\Comment\HintInfluence:
				$forward = Comment\HintInfluenceController::class;
				break;

			case $this->entity instanceof \MA\Entity\Comment\HintRelation:
				$forward = Comment\HintRelationController::class;
				break;

			case $this->entity instanceof \MA\Entity\Comment\Note:
				$forward = Comment\NoteController::class;
				break;

			case $this->entity instanceof \MA\Entity\Comment\Simulation:
				$forward = Comment\SimulationController::class;
				break;

			default:
				return $this->notFoundAction();
		}
		$this->getEvent()->setParam("forward", $forward);

		return $this->forward()->dispatch($forward, [
			'action' => $this->params()->fromRoute('action'),
			'id'	 => $this->entity->getId(),
			//FIXME: Controller for nav pages is losed on routeMatch after forward 
			'controller' => self::class,
			//FIXME: Cannot send more params that expecifieds on navigation
			//'entity' => $this->entity,
		]);
	}
}
