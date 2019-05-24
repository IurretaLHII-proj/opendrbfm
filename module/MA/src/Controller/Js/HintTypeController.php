<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class HintTypeController extends \Base\Controller\Js\AbstractActionController
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
			->get(\MA\Form\HintTypeForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$em->flush();

				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/hint/type/detail/json"),
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
    public function hintsAction()
    {
		$e  = $this->getEntity();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
		$r  = $em->getRepository("MA\Entity\Hint");

		$collection = $r->findByType($this->entity);

		$paginator = $this->getPaginator($collection);

		$metadata = $this->hal()->getMetadataMap()->get("MA\Entity\Hint");
		$metadata->setHydrator(new \MA\Hydrator\Expanded\HintHydrator());
		$metadata->setMaxDepth(4);

		$payload = $this->prepareHalCollection($paginator, 'process/hint/type/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}
}
