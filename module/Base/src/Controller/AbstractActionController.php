<?php

namespace Base\Controller;

use Zend\Mvc\MvcEvent,
	Zend\Mvc\Controller\AbstractActionController as BaseAbstractActionController;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator,
	Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use BjyAuthorize\Exception\UnAuthorizedException;

abstract class AbstractActionController extends BaseAbstractActionController
{
	/**
	 * @param \Zend\Service\Navigation
	 */
	protected $navService;

	/**
	 * @var \Base\Service\AbstractService
	 */
	protected $service;

	/**
     * @var Doctrine\ORM\EntityManager
	 */
    protected $em;

	/**
	 * @var mixed
	 */
	protected $entity;

	/**
     * @return Doctrine\ORM\EntityManager
	 */
    public function getEntityManager()
    {
        if ($this->em === null) {
            $this->em = $this->getServiceLocator()
                             ->get('Doctrine\ORM\EntityManager')
                             ;
        }    

        return $this->em;
    }

	/**
	 * @inheritDoc
	 */
	public function onDispatch(MvcEvent $e)
    {
        $action = $this->params()->fromRoute('action');
        $id 	= $this->params()->fromRoute('id');
        $em 	= $this->getEntityManager();

		if (null === ($repository = $this->getNavService()->getControllerRepository($this))) {
			throw new \InvalidArgumentException(sprintf("No reporisory especified for %s", static::class));
		}

        if ($id) {
            if (null === ($this->entity = $em->find($repository, $id))) {
                return $this->notFoundAction();
            }

			if ($this->entity instanceof ResourceInterface && !$this->isAllowed($this->entity, $action)) {
				if ($this->zfcUserAuthentication()->hasIdentity()) {
					throw new UnAuthorizedException(sprintf("%s:%s",$this->entity->getResourceId(), $action));
				}
				else {
					return $this->redirect()->toRoute("zfcuser/login");
				}
			}

			$this->navService->generateNavigation(
				$this,$this->getServiceLocator()->get('navigation'), $this->getEvent()->getRouteMatch());
        }

        return parent::onDispatch($e);
    }

	/**
	 * @inheritDoc
	 */
	public function attachDefaultListeners()
	{
		parent::attachDefaultListeners();
		$events = $this->getEventManager();
		$events->attach(MvcEvent::EVENT_DISPATCH, [$this, 'injectDefaultVariables']);
	}

	/**
	 * @param MvcEvent $e
	 */
	public function injectDefaultVariables(MvcEvent $e)
	{
        $result = $e->getResult();

        if (!$result instanceof ModelInterface || $result->terminate()) {
            return;
        }

		if ($this->getEntity()) {
			$this->_injectDefaultVariables($result);
		}
	}

	/**
	 * @param ModelInterface $model
	 */
	protected function _injectDefaultVariables(ModelInterface $model)
	{
		//inject variable here
	}

	/**
	 * @param \Base\Service\AbstractService $service 
	 * @return AbstractActionController
	 */
	public function setService(\Base\Service\AbstractService $service)
	{
		$this->service = $service;
		return $this;
	}

	/**
	 * @return \Base\Service\AbstractService 
	 */
	public function getService()
	{
		return $this->service;
	}

	/**
	 * @param mixed
	 * @return AbstractActionController
	 */
	public function setEntity($entity)
	{
		$this->entity = $entity;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getEntity()
	{
		return $this->entity;
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

	/**
	 * @param mixed $entity
	 * @param string $route
	 * @param string $id
	 * @return \ZF\Hal\Entity
	 */
	public function prepareHalEntity($entity, $route, $id = 'id')
	{
		if ($entity instanceof \ZF\Hal\Entity 
			&& ($entity->getLinks()->has('self') || !$entity->id)
		) {
			return $entity;
		}

		return $this->plugin('hal')->createEntity($entity, $route, $id);
	}

	/**
	 * @return array
	 *
	 */
	public function prepareNavigationParams()
	{
		return [
			'id' => $this->getEntity()->getId(),
		];
	}

	/**
	 * @param AbstractActionController $controller
	 * @param mixed $e
	 * @return AbstractActionController
	 *
	 */
	public function prepareParentNavigation(AbstractActionController $controller, $e = null)
	{
		if ($e !== null) {

			$method = "get" . ucFirst($e);	

			$controller->setEntity($this->getEntity()->$method());
		}
		else {
			throw new \InvalidArgumentException(sprintf("Empty entity in %s navigation", static::class));
		}

		return $this;
	}
    
    /**
     * Get navService.
     *
     * @return ServiceNavigation.
     */
    public function getNavService()
    {
        return $this->navService;
    }
    
    /**
     * Set navService.
     *
     * @param ServiceNavigation navService the value to set.
	 * @return AbstractActionController.
     */
    public function setNavService($navService)
    {
        $this->navService = $navService;
        return $this;
    }

	/**
	 * @param string $event
	 * @param mixed $entity
	 * @return mixed
	 */
	public function triggerService($event, $entity)
	{
		return $this->navService->triggerService($event, $entity);
	}

	/**
	 * @param array $collection
	 * @param int $l limit of Paginator
	 * @param int $p page of Paginator
	 * @param int $ln limit query name of Paginator
	 * @param int $pn page query name of Paginator
	 * @return Paginator
	 */
	public function getPaginator(array $collection, $l = 5, $p = 1, $ln = 'limit', $pn = 'page')
	{
		$paginator = new Paginator(new ArrayAdapter($collection));
		$paginator->setItemCountPerPage((int) $this->params()->fromQuery($ln, $l));
		$paginator->setCurrentPageNumber((int) $this->params()->fromQuery($pn, $p));

		return $paginator;
	}
}
