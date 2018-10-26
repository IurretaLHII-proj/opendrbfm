<?php

namespace Base\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\EventManager\SharedListenerAggregateInterface;
use Zend\EventManager\SharedEventManagerInterface;
use Zend\View\Model\ModelInterface;
use Zend\View\Model\ViewModel;
use Zend\Paginator\Paginator,
	Zend\Paginator\Adapter\ArrayAdapter;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use BjyAuthorize\Exception\UnAuthorizedException;
use Base\Controller\AbstractActionController as BaseAbstractActionController;

abstract class AbstractActionController extends BaseAbstractActionController
{
	/**
	 * @inheritDoc
	 */
	public function onDispatch(MvcEvent $e)
    {
		try {
			return parent::onDispatch($e);
		}
		catch (UnAuthorizedException $e) {
			throw new \ZF\ApiProblem\Exception\DomainException('Ypu are not authorized', 403);
		}
    }
}
