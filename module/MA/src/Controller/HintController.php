<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;

class HintController extends \Base\Controller\AbstractActionController
{
	protected function test($stage, $hint)
	{
		$stg = new \MA\Entity\Stage;

		foreach ($stage->getChildren() as $child) {

			$_stg = new \MA\Entity\Stage;

			foreach ($child->getHints() as $_hint) {
				if ($_hint->hasParent($hint)) {
					$_stg->addHint(clone $_hint);
				}
			}

			if (!$child->getChildren()->isEmpty()) {
				foreach ($this->test($child, $hint)->getChildren() as $c) {
					$_stg->getChildren()->add($c);
				}
			}

			$stg->addChild($_stg);
		}

		return $stg;
	}

	/**
	 * @return ViewModel
	 */
    public function detailAction()
    {
		$process = new \MA\Entity\Process;

		$hint     = $this->getEntity();
		$stage    = $hint->getStage();

		$_s = $this->test($stage, $hint);
		$_s->addHint(clone $hint);
		//FIXME to have original object on tpl. $hint->setStage($stage);

		while(null !== ($parent = $stage->getParent())) {
			
			$stg = new \MA\Entity\Stage;
			if (isset($_stg)) {
				$stg->addChild($_stg);
			}
			else {
				$last = $stg;
			}

			foreach ($parent->getHints() as $_hint) {
				if ($_hint->hasChild($hint)) $stg->addHint(clone $_hint);
			}

			$_stg   = $stg;
			$stage  = $parent;
		}

		if (isset($stg)) {
			$last->addChild($_s);
		}
		else {
			$stg = $_s;
		}

		$process->addStage($stg);

		return new ViewModel([
			'process' => $process,
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
