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
    public function detailAction()
    {
		return new ViewModel([
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
