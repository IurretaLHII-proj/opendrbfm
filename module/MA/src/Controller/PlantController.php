<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use Zend\Json\Json;
use ZF\Hal\View\HalJsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class PlantController extends \Base\Controller\AbstractActionController
{
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
