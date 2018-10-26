<?php

namespace MA\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Paginator\Paginator,
	Zend\Paginator\Adapter\ArrayAdapter;
use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class IndexController extends AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
    {
		$resource = "MA\Entity\Process";

		if (!$this->zfcUserAuthentication()->hasIdentity()) {
			return $this->redirect()->toRoute("zfcuser/login");
		}

        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository($resource)->findBy([],['created' => 'DESC']);

		$paginator = new Paginator(new ArrayAdapter($collection));
		$paginator->setItemCountPerPage((int) $this->params()->fromQuery('limit', 5));
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));

		return new ViewModel([
			'hal' => $this->prepareHalCollection($paginator, 'process/json'),
		]);
    }

	/**
	 * @param Paginator $paginator
	 * @param string $route
	 * @param array $params
	 * @param array $options
	 * @param string $id
	 * @return \ZF\Hal\Collection
	 */
	public function prepareHalCollection(Paginator $pag, $route, $params = [], $options = [], $id = 'id')
	{
		$hal = $this->plugin('hal');

		$resource = new \ZF\Hal\Collection($pag);

		$resource->setCollectionRoute($route);
		$resource->setRouteIdentifierName($id);
		$resource->setEntityRoute($route);
		$resource->setPageSize($pag->getItemCountPerPage());

		$options = \Zend\Stdlib\ArrayUtils::merge(
			$options, ['query' => ['limit' => $pag->getItemCountPerPage()]]
		);

		$resource->setCollectionRouteParams($params);
		$resource->setCollectionRouteOptions($options);

		try {
			$resource->setPage($pag->getCurrentPageNumber());	
		}
		catch (\ZF\Hal\Exception\InvalidArgumentException $e) {
			die($e->getMessage());
		}

		$collection = $hal->createCollection($resource, $route);	

		if (!$collection->getLinks()->has('self')) {
			$hal->injectSelfLink($collection, $route);
		}


		return $collection;
	}
}
