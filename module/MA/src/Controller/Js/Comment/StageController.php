<?php

namespace MA\Controller\Js\Comment;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;
use MA\Entity\Comment\Stage as StageComment;

class StageController extends AbstractController
{
	/**
	 * @var string
	 */
	protected $__className__ = StageComment::class;

	/**
	 * @inheritDoc
	 */
	protected function getClassName() {
		return (string) $this->__className__;
	}
}
