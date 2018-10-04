<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class ProcessController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\Process")->findBy([],['created' => 'DESC']);

		$payload = $this->prepareHalCollection($this->getPaginator($collection), 'user/issue/index');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return JsonViewModel
	 */
	public function stageAction()
	{
		$e    = new \MA\Entity\Stage;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\StageForm::class);

		$e->setProcess($this->getEntity());

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
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
				$this->getResponse()->setStatusCode(422);
				$payload = $form->getMessages();
			}
		}

		return new HalJsonModel($payload);
	}
}
