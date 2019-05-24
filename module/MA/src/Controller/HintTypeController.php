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
				['priority' => 'DESC']
			);

		$paginator = $this->getPaginator($collection);

		$metadata = $this->hal()->getMetadataMap()->get("MA\Entity\Hint");
		$metadata->setHydrator(new \MA\Hydrator\Expanded\HintHydrator());
		$metadata->setMaxDepth(4);

		return new ViewModel([
			'collection' => $this->prepareHalCollection(
				$paginator, 
				'process/hint/type/detail/json', 
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
				'hal'    => $this->prepareHalEntity($entity, "process/hint/type/detail/json")
			]);
		}
	}
}
