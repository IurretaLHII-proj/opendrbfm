<?php

namespace MA\Controller\Js\Comment;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;
use MA\Entity\Comment\Version as VersionComment;

class VersionController extends AbstractController
{
	/**
	 * @var string
	 */
	protected $__className__ = VersionComment::class;

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}
}
