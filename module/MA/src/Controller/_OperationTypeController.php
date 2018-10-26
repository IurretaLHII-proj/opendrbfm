<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class OperationTypeController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository(\MA\Entity\OperationType::class)
			->findBy([],['created' => 'DESC']);

		return new ViewModel([
			'collection' => $this->getPaginator($collection, 10),
		]);
    }

	/**
	 * @return ViewModel
	 */
    public function detailAction()
    {
		$e    = new \MA\Entity\Operation;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\OperationForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [
				'action' => 'operation',
			], [
				'query' => ['redirect' => $this->url()->fromRoute(null, [], [], true)]
			], true));

        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		return new ViewModel([
			'form' => $form,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function operationAction()
    {
		$e    = new \MA\Entity\Operation;
		$e->setType($this->getEntity());

		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\OperationForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		$r = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect'));

		if ($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$this->getEntityManager()->persist($e);
				$this->getEntityManager()->flush();

				return $this->redirect()->toUrl($r ? $r : 
					$this->url()->fromRoute('process/operation/detail', [
						'id' => $this->getEntity()->getId(),
					]));
			}
		}

		return new ViewModel([
			'form' => $form,
			'redirect' => $r,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function addAction()
    {
		$e    = new \MA\Entity\OperationType;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\OperationTypeForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$this->getEntityManager()->persist($e);
				$this->getEntityManager()->flush();

				$r = $this->params()->fromQuery('redirect', $this->url()->fromRoute('process/operation/type/detail', [
					'id' => $e->getId(),
				]));

				return $this->redirect()->toUrl($r);
			}

		}

		return new ViewModel(['form' => $form,]);
	}

	/**
	 * @inheritDoc
	 */
	protected function _injectDefaultVariables(ModelInterface $model)
	{
		if (null !== ($entity = $this->getEntity())) {
			$model->setVariables([
				'entity' => $entity,
			]);
		}
	}
}
