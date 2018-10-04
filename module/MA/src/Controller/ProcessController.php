<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class ProcessController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function addAction()
    {
		$e    = new \MA\Entity\Process;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\ProcessForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			if ($form->isValid()) {
				//$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$this->getEntityManager()->persist($e);
				$this->getEntityManager()->flush();

				$r = $this->params()->fromQuery('redirect', $this->url()->fromRoute('process/detail', [
					'id' => $e->getId(),
				]));

				return $this->redirect()->toUrl($r);
			}

		}

		return new ViewModel(['form' => $form,]);
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
				'hal'    => $this->prepareHalEntity($entity, "process/detail/json")
			]);
		}
	}
}
