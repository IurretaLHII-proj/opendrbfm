<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class HintController extends \Base\Controller\AbstractActionController
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
	 * @return ViewModel
	 */
    public function actionsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\Action\Hint")
			->findBy(
				['source'  => $this->getEntity()],
				['created' => 'DESC']
			);

		$paginator = $this->getPaginator($collection, 10);
		return new ViewModel([
			'actionsHal' => $this->prepareHalCollection($paginator, 'process/hint/detail/json', ['action' => 'actions']),
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
				'hal'    => $this->prepareHalEntity($entity, "hint/detail/json")
			]);
		}
	}
}
