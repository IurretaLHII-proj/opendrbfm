<?php

namespace MA\Controller;

use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class CustomerController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\CustomerForm::class);

		$form->setAttribute('action', $this->url()->fromRoute('customer/add', [], [
				'query' => ['redirect' => $this->url()->fromRoute(null, [], [], true)]
			], true));

        $form->setHydrator(new DoctrineHydrator($em));

		$collection = $em->getRepository(\MA\Entity\Customer::class)
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
		$e    = new \MA\Entity\Customer;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\CustomerForm::class);

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
					$this->url()->fromRoute('process/customer/detail', [
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
		$model->setVariables([
			'entity' => $this->entity,
		]);
	}
}
