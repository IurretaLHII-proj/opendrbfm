<?php

namespace MA\Controller;

use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class UserController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
		if (null === ($entity = $this->zfcUserAuthentication()->getIdentity())) {
			return $this->redirect()->toRoute(\ZfcUser\Controller\UserController::ROUTE_LOGIN);
		}

		return $this->redirect()->toRoute('user/detail', [
			'id' => $entity->getId(), 
			'action' => 'detail'
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function addAction()
    {
		$e    = new \MA\Entity\User;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()->get('ZfcUserRegisterForm');
		$form->add([
			'type' => 'MultiCheckbox',
			'name' => 'roles',
            'attributes' => [ 
                'class' => 'form-control'
			],
			'options' => [
				'label' => 'Roles',
				'value_options' => [
					\MA\Entity\User::ROLE_USER => \MA\Entity\User::ROLE_USER,
					\MA\Entity\User::ROLE_ADMIN => \MA\Entity\User::ROLE_ADMIN,
					\MA\Entity\User::ROLE_SUPER => \MA\Entity\User::ROLE_SUPER,
				]
			],
		]);
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			if ($form->isValid()) {

        		$bcrypt = new \Zend\Crypt\Password\Bcrypt;
        		$bcrypt->setCost(14);
        		$e->setPassword($bcrypt->create($e->getPassword()));

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$this->getEntityManager()->persist($e);
				$this->getEntityManager()->flush();

				$r = $this->params()->fromQuery('redirect', $this->url()->fromRoute('user/detail', [
					'id' => $e->getId(),
				]));

				return $this->redirect()->toUrl($r);
			}

		}
		return new ViewModel([
			'form' => $form,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function detailAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\AbstractAction")
			->findBy(
				['user' => $this->entity],
				['created' => 'DESC']
			);

		$paginator = $this->getPaginator($collection);
		return new ViewModel([
			'actionsHal' => $this->prepareHalCollection($paginator, 'user/detail/json', ['action' => 'actions']),
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function changepasswordAction()
    {
		//forward
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
