<?php

namespace MA\Controller\Js\Comment;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;
use MA\Entity\Comment\HintRelation as HintComment;

class HintRelationController extends AbstractController
{
	/**
	 * @var string
	 */
	protected $__className__ = HintComment::class;

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}
}
