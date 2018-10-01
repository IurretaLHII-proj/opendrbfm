<?php

namespace Base\Service;

use Zend\ServiceManager\ServiceManager,
	Zend\ServiceManager\ServiceLocatorInterface,
	Zend\ServiceManager\ServiceLocatorAwareInterface
	;

use Zend\EventManager\EventManager
	, Zend\EventManager\EventInterface
	, Zend\EventManager\EventManagerInterface
	, Zend\EventManager\EventManagerAwareInterface
	, Zend\EventManager\SharedEventManagerInterface
	, Zend\EventManager\SharedListenerAggregateInterface
	;

abstract class AbstractService implements 
	ServiceLocatorAwareInterface
	, EventManagerAwareInterface
	, SharedListenerAggregateInterface
{

	const EVENT_CREATE = 'create';
	const EVENT_UPDATE = 'update';
	const EVENT_REMOVE = 'remove';

	/**
	 * @var array
	 */
	protected $listeners = [];

	/**
	 * @var array
	 */
	protected $listenersConfig = [];

	/**
	 * @var EventManagerInterface
	 */
	protected $events;

	/**
	 * @var ServiceLocatorInterface
	 */
	protected $sm;

	/**
     * @var Doctrine\ORM\EntityManager
	 */
    protected $em;

	/**
	 * FIXME
	 * @inheritDoc
	 */
	public function detachShared(SharedEventManagerInterface $ev)
	{
		foreach ($this->listeners as $i => $listener) {
			if ($ev->detach([self::class, static::class], $listener)) {
			//if ($ev->detach($listener)) {
				unset($this->listeners[$i]);
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function attachShared(SharedEventManagerInterface $ev)
	{
		foreach ($this->listenersConfig as $config) {
			if (!isset($config['services'])) {
				throw new \InvalidArgumentException(sprintf("listening services not defined on %s",
					get_class($this)));
			}
			if (!isset($config['events'])) {
				throw new \InvalidArgumentException(sprintf("listening events not defined on %s",
					get_class($this)));
			}
			if (!isset($config['callback'])) {
				throw new \InvalidArgumentException(sprintf("callback not defined on %s",
					get_class($this)));
			}
			$services = is_array($config['services']) ? $config['services'] : [$config['services']];
			$events	  = is_array($config['events']) ? $config['events'] : [$config['events']];
			$callback = [$this, $config['callback']];
			$priority = isset($config['priority']) ? (int) $config['priority'] : 1;

			$this->listeners[] = $ev->attach($services, $events, $callback, $priority);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function setEventManager(EventManagerInterface $events)
	{
		$ids = [self::class, static::class];
		$events->setIdentifiers($ids);
		$this->events = $events;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getEventManager()
	{
		if ($this->events === null) {
			$this->events = new EventManager;
		}
		return $this->events;
	}

    /**
	 * @inheritDoc
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->sm = $serviceLocator;
		return $this;
	}

    /**
	 * @inheritDoc
     */
    public function getServiceLocator()
	{
		return $this->sm;
	}

	/**
	 * @var array $conf
	 * @return AbstractService
	 */
	public function setListenersConfig(array $conf)
	{
		$this->listenersConfig = $conf;
		return $this;	
	}

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
	 * @return array
	 */
	public function getListeners()
	{
		return $this->listeners;
	}
}

