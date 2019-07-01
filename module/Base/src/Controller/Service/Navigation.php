<?php

namespace Base\Controller\Service;

use Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\ServiceManager;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\Navigation\Navigation as BaseNav;
use Zend\Mvc\Router\RouteMatch;
use Zend\View\Model\ViewModel;
use Zend\Permissions\Acl\Resource\ResourceInterface;

use Base\Controller\AbstractActionController;

class Navigation
{
	/**
	 * @var ServiceLocatorInterface
	 */
	protected $sm;

	/**
	 * @var array 
	 */
	protected $crtlConfig = [];

	/**
	 * @var array 
	 */
	protected $svceConfig = [];

	/**
	 * @var AbstractActionController[]
	 */
	protected $_loadedControllers = [];

	/**
	 * @var AbstractService[]
	 */
	protected $_loadedServices = [];

	/**
	 * @param ServiceLocatorInterface $sm
	 * @param array $crtlConfig
	 */
	public function __construct(ServiceLocatorInterface $sm, array $controllers, array $services)
	{
		$this->sm = $sm;
		$this->crtlConfig = $controllers;
		$this->svceConfig = $services;
	}

	/**
	 * @param AbstractActionController $controller
	 */
	public function generateNavigation(AbstractActionController $controller, BaseNav $nav, RouteMatch $m)
	{
		$className = get_class($controller);

		if (!isset($this->crtlConfig[$className])) {
			return;
		}

		$args = $this->crtlConfig[$className];

		if (isset($args['parent']) && is_array($args['parent'])) {

			if (isset($args['parent']['controller'])) { 
				//If controller not set asume the page is allready in navigation
				$parent = $this->getControllerByName($args['parent']['controller']); 

				$entity = isset($args['parent']['entity']) ? $args['parent']['entity'] : null;

				$controller->prepareParentNavigation($parent, $entity);

				$this->generateNavigation($parent, $nav, $m);
			}

			$navs = $nav->findAllByRoute($args['parent']['route']);

			if (!count($navs)) {
				throw new \InvalidArgumentException(sprintf("Cannot find %s parent route in %s nav",
					$args['parent']['route'], $className));
			}

			$_nav;
			foreach ($navs as $page) {
				if ($page->getAction() === $args['parent']['action']) {
					$_nav = $page;
				}
			   	elseif (null !== ($n = $page->findOneByAction($args['parent']['action']))) {
					$_nav = $n;
				}
			}
			if (!$_nav) {
				throw new \InvalidArgumentException(sprintf("Cannot find %s parent action for %s route",
					$args['parent']['action'], $args['parent']['route']));
			}	

			$nav = $_nav;
		}

		if (isset($args['page'])) {
			$curr = $args['page'];

			$params = $controller->prepareNavigationParams();

			if (!$controller->getEntity() instanceof ResourceInterface) {
				throw new \InvalidArgumentException(sprintf("%s must implement %s",
					get_class($controller->getEntity()), ResourceInterface::class));
			}

			$page = [
				'label' => (string) $controller->getEntity(), //curr['action'],
				'route' => $curr['route'],
				'action' => $curr['action'],
				'controller' => isset($curr['controller']) ? $curr['controller'] : get_class($controller),
				'route_match' => $m,
				'params' => $params,
				'resource' => $controller->getEntity(),
				'privilege' => $curr['action'],
				'class' => 'nav-link',
				'visible' => false,
				'pages' => [],
			];

			foreach ($curr['pages'] as $i) {
				$page['pages'][] = [
					'label' => ucFirst($i['action']), 
					'route' => $i['route'], 
					'action' => $i['action'], 
					'controller' => isset($i['controller']) ? $i['controller'] : get_class($controller),
					'route_match' => $m,
					'params' => $params,
					'resource' => $controller->getEntity(),
					'privilege' => $i['action'],
					'class' => 'nav-link',
				]; 
			}

			$nav->addPage($page);
		}
	}

	/**
	 * @param AbstractActionController $controller
	 * @return array 
	 */
	protected function getControllerConfig(AbstractActionController $controller)
	{
		$className = get_class($controller);

		if (!isset($this->crtlConfig[$className])) {
			return [];
		}

		return $this->crtlConfig[$className];
	}

	/**
	 * @param AbstractActionController $controller
	 * @return mixed
	 */
	public function getControllerRepository(AbstractActionController $controller)
	{
		if ([] === ($args = $this->getControllerConfig($controller))) {
			return;
		}

		return isset($args['repository']) ? (string) $args['repository'] : null;
	}

	/**
	 * @param strin $name
	 * @return AbstractActionController|null
	 */
	protected function getControllerByName($name)
	{
		if (array_key_exists($name, $this->_loadedControllers)) {
			return $this->_loadedControllers[$name];
		}	

		$this->_loadedControllers[$name] = $this->sm->get('ControllerManager')->get($name);

		return $this->_loadedControllers[$name];
	}

	/**
	 * @param strin $name
	 * @return AbstractInterface|null
	 */
	protected function getServiceByName($name)
	{
		if (array_key_exists($name, $this->_loadedServices)) {
			return $this->_loadedServices[$name];
		}	

		$this->_loadedServices[$name] = $this->sm->get($name);

		return $this->_loadedServices[$name];
	}

	/**
	 * @param AbstractActionController $controller
	 * @return mixed
	 */
	public function getServiceByController(AbstractActionController $controller)
	{
		if ([] === ($args = $this->getControllerConfig($controller))) {
			return;
		}

		return isset($args['service']) ? $this->getServiceByName((string) $args['service']) : null;
	}

	/**
	 * @param string $className
	 * @return AbstractActionController
	 */
	public function getServiceByRepository($className)
	{
		foreach ($this->svceConfig as $svceName => $args) {
			if (!(isset($args['entities']) && in_array((string) $className, $args['entities']))) {
				continue;
			}
			
			return $this->getServiceByName($svceName); 
		}	
	}

	/**
	 * @param string $event
	 * @param mixed $entity
	 * @param array $argv
	 * @return mixed
	 */
	public function triggerService($event, $entity, $argv = [])
	{
		if (null === ($service = $this->getServiceByRepository(get_class($entity)))) {
			throw new \InvalidArgumentException(sprintf("Unable to find service for given %s entity",
				get_class($entity)));
		}

		return $service->getEventManager()->trigger($event, $entity, $argv);
	}

	/**
	 * @param SharedEventManagerInterface $shared
	 * @return Navigation
	 */
	public function attachServices(SharedEventManagerInterface $shared)
	{
		foreach ($this->svceConfig as $name => $args) {
			$shared->attachAggregate($this->getServiceByName($name));
		}

		return $this;
	}	

	/**
	 * @param SharedEventManagerInterface $shared
	 * @return Navigation
	 */
	public function detachServices(SharedEventManagerInterface $shared)
	{
		foreach ($this->svceConfig as $name => $args) {
			$shared->detachAggregate($this->getServiceByName($name));
		}

		return $this;
	}	
}
