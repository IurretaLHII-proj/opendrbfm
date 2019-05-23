<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class VersionTypeController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\VersionTypeForm::class);

		$form->setAttribute('action', $this->url()->fromRoute('process/version/type/add', [], [
				'query' => ['redirect' => $this->url()->fromRoute(null, [], [], true)]
			], true));

        $form->setHydrator(new DoctrineHydrator($em));

		$collection = $em->getRepository(\MA\Entity\VersionType::class)
			->findBy([],['created' => 'ASC']);

		$paginator = $this->getPaginator($collection, 100);

		return new ViewModel([
			'collection' 	=> $paginator,
			'form' 			=> $form,
		]);
    }

	/**
	 * @return ViewModel
	 */
    public function addAction()
    {
		$e    = new \MA\Entity\VersionType;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\VersionTypeForm::class);

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
					$this->url()->fromRoute('process/version/type/detail', [
						'id' => $e->getId(),
					]));
			}

		}

		return new ViewModel([
			'title'		=> 'New version type',
			'form' 	   	=> $form,
			'redirect'	=> $r,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function editAction()
    {
		$e    = $this->getEntity();
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\VersionTypeForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		$r = $this->params()->fromPost('redirect', $this->params()->fromQuery('redirect'));

		if ($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$this->getEntityManager()->flush();

				return $this->redirect()->toUrl($r ? $r : 
					$this->url()->fromRoute('process/version/type/detail', [
						'id' => $e->getId(),
					]));
			}

		}

		$model = new ViewModel([
			'title'		=> sprintf("Edit %s", $e),
			'form' 	   	=> $form,
			'redirect'	=> $r,
		]);
		$model->setTemplate('ma/version-type/add');
		return $model;
	}

	/**
	 * @return ViewModel
	 */
    public function detailAction()
    {
		return new ViewModel([
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
