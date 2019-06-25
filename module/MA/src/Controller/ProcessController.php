<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use Zend\Json\Json;
use DOMPDFModule\View\Model\PdfModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;
use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use MA\Entity\ProcessInterface,
	MA\Entity\VersionInterface;

class ProcessController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function indexAction()
	{
		return new ViewModel([
			'tpl' => false,
		]);
	}

	/**
	 * @return ViewModel
	 */
    public function tplAction()
	{
		$model = new ViewModel([
			'tpl' => true,
		]);
		$model->setTemplate('ma/process/index');
		return $model;
	}

	/**
	 * @return ViewModel
	 */
    public function detailAction()
    {
		/*$version  = $this->getEntity()->getVersions()->first();

		if ($version) {
			$children = $version->getChildren(true);
		}
		else {
			$children = new ArrayCollection;
		}
		$paginator = $this->getPaginator($children->toArray(), $children->count());*/

		return new ViewModel([
			//'stageId'     => $this->params()->fromQuery('stage'),
			//'version' 	  => $version,
			//'stage'		  => $version,
			//'childrenHal' => $this->prepareHalCollection($paginator, 'process/detail/json'),
		]);
	}

	protected function versionHierarchy(ProcessInterface $process, VersionInterface $version)
	{
		$cloned = clone $version;
		$cloned->setProcess($process);
		if (!$version->getChildren()->isEmpty()) {
			$children = $version->getChildren()->toArray();
			while ($child = array_shift($children)) {
				$cloned->addChild($this->versionHierarchy($process, $child));
			}
		}
		$this->triggerService($this->params()->fromRoute('action'), $cloned);
		return $cloned;
	}

	/**
	 * @return Response
	 */
    public function cloneAction()
    {
		$e = $this->getEntity();
		$c = clone $e;

		$parents = $e->getVersions()->filter(function($e) {return $e->isParent();})->toArray();

		while (null !== ($parent = array_shift($parents))) {
			$c->addVersion($this->versionHierarchy($c, $parent));
		}

		$this->triggerService($this->params()->fromRoute('action'), $c);

		$this->getEntityManager()->persist($c);
		$this->getEntityManager()->flush();

		return $this->redirect()->toRoute('process/detail', ['id' => $c->getId()]);
	}

	/**
	 * @return Response
	 */
    public function deleteAction()
    {
		//$this->triggerService($this->params()->fromRoute('action'), $this->entity);
		$this->getEntityManager()->remove($this->entity);
		$this->getEntityManager()->flush();

		return $this->redirect()->toUrl($this->params()->fromQuery('redirect', '/'));
	}

	/**
	 * @return ViewModel
	 */
    public function actionsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$collection = $em->getRepository("MA\Entity\AbstractProcessAction")
			->findBy(
				['process' => $this->getEntity()],
				['created' => 'DESC']
			);

		$paginator = $this->getPaginator($collection, 10);
		return new ViewModel([
			'actionsHal' => $this->prepareHalCollection($paginator, 'process/detail/json', ['action' => 'actions']),
		]);
	}

	/**
	 * @return PdfModel
	 */
	public function reportAction()
	{
		return $this->pdfAction();
	}

	/**
	 * @return PdfModel
	 */
	public function pdfAction()
	{
		$e = $this->getEntity();

		if ($this->getRequest()->isPost()) {

			//set_time_limit(0);

			$values = $this->getRequest()->getPost();
			foreach ($e->getVersions() as $version) {
				if (!in_array((string) $version->getId(), $values['versions'])) {
					$e->removeVersion($version);
				}
				else {
					foreach ($version->getStages() as $stage) {
						if (!in_array((string) $stage->getId(), $values['stages'])) {
							$version->removeStage($stage);
						}
						else {
							foreach ($stage->getHints() as $error) {
								if (!in_array((string) $error->getId(), $values['hints'])) {
									$stage->removeHint($error);
								}
								else {
									foreach ($error->getReasons() as $r) {
										if (!in_array((string) $r->getId(), $values['reasons'])) {
											$error->removeReason($r);
										}
										else {
											foreach ($r->getInfluences() as $i) {
												if (!in_array((string) $i->getId(), $values['influences'])) {
													$r->removeInfluence($i);
												}
											}
										}	
									}
								}	
							}
						}	
					}
				}
			}

			$pdf = new PdfModel([
				'entity' => $e,
			]);

			$pdf->setTerminal(true);
			//$pdf->setOption('basePath', '/');
        	//$pdf->setOption('filename', $e . '-report');	// "pdf" extension is automatically appended
        	$pdf->setOption('paperOrientation', 'landscape'); // Defaults to "portrait"
        	$pdf->setOption('paperSize', 'a4');               	// Defaults to "8x11"
    		return $pdf;
		}
		$model = new ViewModel([
			'entity' => $this->getEntity(),
		]);
		return $model;
	}

	/**
	 * @inheritDoc
	 */
	protected function _injectDefaultVariables(ModelInterface $model)
	{
		if (null !== ($entity = $this->getEntity())) {
			$model->setVariables([
				'entity' => $entity,
				'hal'    => $this->prepareHalEntity($entity, "process/detail/json")
			]);
		}
	}
}
