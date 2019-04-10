<?php

namespace MA\Controller;

use Zend\View\Model\ViewModel,
	Zend\View\Model\ModelInterface;
use DOMPDFModule\View\Model\PdfModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use BjyAuthorize\Exception\UnAuthorizedException;
use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;

class ProcessController extends \Base\Controller\AbstractActionController
{
	/**
	 * @return ViewModel
	 */
    public function addAction()
    {
		$e    = new \MA\Entity\Process;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\ProcessForm::class);

		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData($this->getRequest()->getPost());
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$this->getEntityManager()->persist($e);
				$this->getEntityManager()->flush();

				$r = $this->params()->fromQuery('redirect', $this->url()->fromRoute('process/detail', [
					'id' => $e->getId(),
				]));

				return $this->redirect()->toUrl($r);
			}

		}

		return new ViewModel(['form' => $form,]);
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
	public function pdfAction()
	{
		$e = $this->getEntity();

		$pdf = new PdfModel([
			'entity' => $e,
		]);
        //$pdf->setOption('filename', $e . '-report');		// "pdf" extension is automatically appended
        $pdf->setOption('paperSize', 'a4');               	// Defaults to "8x11"
        //$pdf->setOption('paperOrientation', 'landscape'); 	// Defaults to "portrait"
    	return $pdf;
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
