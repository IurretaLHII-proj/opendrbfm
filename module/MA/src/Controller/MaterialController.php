<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class MaterialController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\MaterialForm::class);

		$form->setAttribute('action', $this->url()->fromRoute('process/material/add', [], [
				'query' => ['redirect' => $this->url()->fromRoute(null, [], [], true)]
			], true));

        $form->setHydrator(new DoctrineHydrator($em));

		$collection = $em->getRepository(\MA\Entity\OperationType::class)
			->findBy([],['created' => 'ASC']);

		$paginator = $this->getPaginator($collection, 100);

		return new ViewModel([
			'collection' 	=> $paginator,
			'collectionHal' => $this->prepareHalCollection($paginator, 'process/operation/type/json'),
			'form' 			=> $form,
		]);
    }

	/**
	 * @return ViewModel
	 */
    public function addAction()
    {
		$e    = new \MA\Entity\Material;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\MaterialForm::class);

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
					$this->url()->fromRoute('process/material/detail', [
						'id' => $e->getId(),
					]));
			}

		}

		return new ViewModel([
			'form' 	   => $form,
			'redirect' => $r,
		]);
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
