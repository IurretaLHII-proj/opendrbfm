<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class HintTypeController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function detailAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\Hint")
			->findBy(
				['type' => $this->entity],
				['created' => 'DESC']
			);

		$paginator = $this->getPaginator($collection);
		return new ViewModel([
			'collection' => $this->prepareHalCollection(
				$paginator, 
				'process/hint/detail/json', 
				['action' => 'hints']),
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
