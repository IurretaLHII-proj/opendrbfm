<?php

namespace MA\Controller\Js;

use Zend\Mvc\MvcEvent;
use Zend\Json\Json;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
use ZF\Hal\View\HalJsonModel;

class VersionController extends \Base\Controller\Js\AbstractActionController
{
	/**
	 * @return JsonViewModel
	 */
	public function editAction()
	{
		$e	  = $this->getEntity();
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\VersionForm::class);

		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_UPDATE, $e);

				$em->flush();

				$payload = ['payload' => $this->prepareHalEntity($e, "process/version/detail/json")];
			}
			else {
				$ex = new \ZF\ApiProblem\Exception\DomainException('Unprocessable entity', 422);
				$ex->setAdditionalDetails(['errors' => $form->getMessages()]);
				throw $ex;
			}
		}

		return new HalJsonModel($payload);
	}

	/**
	 * @return ViewModel
	 */
    public function cloneAction()
    {
		$e = $this->getEntity();
		$c = clone $e;
		$c->setName("Clone of " . $e->getName());

		foreach ($e->getStages() as $stage) {
			$s = clone $stage;
			foreach ($stage->getImages() as $image) $s->addImage(clone $image);
			foreach ($stage->getHints() as $hint) {
				$h = clone $hint;
				foreach ($hint->getReasons() as $reason) {
					$r = clone $reason;
					foreach ($reason->getNotes() as $note) {
						$r->addNote(clone $note);
					}
					foreach ($reason->getInfluences() as $infl) {
						$i = clone $infl;
						foreach ($infl->getNotes() as $note) {
							$i->addNote(clone $note);
						}
						foreach ($infl->getSimulations() as $sim) {
							$sm = clone $sim;
							foreach ($sim->getImages() as $img) $sm->addImage(clone $img);
							foreach ($sim->getSuggestions() as $note) $sm->addSuggestion(clone $note);
							foreach ($sim->getEffects() as $note) 	$sm->addEffect(clone $note);
							foreach ($sim->getPreventions() as $note) $sm->addPrevention(clone $note);
							$i->addSimulation($sm);
						}
						$r->addInfluence($i);
					}
					$h->addReason($r);
				}
				$s->addHint($h);
			}
			$c->addStage($s);
		}

		$this->triggerService(\MA\Service\VersionService::EVENT_CLONE, $c);

		$this->getEntityManager()->persist($c);
		$this->getEntityManager()->flush();

		return new HalJsonModel([
			'payload' => $this->prepareHalEntity($c, "process/version/detail/json")
		]);
	}

	/**
	 * @return JsonViewModel
	 */
	public function stageAction()
	{
		$e    = new \MA\Entity\Stage;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\StageForm::class);

		$e->setVersion($this->getEntity());
		
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		//$form->get('operations')->setAllowRemove(false);
        $form->setHydrator(new DoctrineHydrator($em));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();

				$payload = ['payload' => $this->prepareHalEntity($e, "process/stage/detail/json")];
			}
			else {
				$ex = new \ZF\ApiProblem\Exception\DomainException('Unprocessable entity', 422);
				$ex->setAdditionalDetails(['errors' => $form->getMessages()]);
				throw $ex;
			}
		}

		return new HalJsonModel($payload);
	}

	/**
	 * @return ViewModel
	 */
    public function stagesAction()
    {
		$e  = $this->getEntity();
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$paginator = $this->getPaginator($e->getStages()->toArray(), $e->getStages()->count());
		$payload = $this->prepareHalCollection($paginator, 'process/version/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return JsonViewModel
	 */
	public function deleteAction()
	{
		$e	  = $this->getEntity();
		$em   = $this->getEntityManager();

		//$this->triggerService(\Base\Service\AbstractService::EVENT_DELETE, $e);

		$em->remove($e);
		$em->flush();

		return new HalJsonModel(['payload' => []]);
	}

	/**
	 * @return ViewModel
	 */
    public function commentsAction()
    {
        $em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

		$paginator = $this->getPaginator($em->getRepository("MA\Entity\comment\Version")
			->findBy(
				['source'  => $this->getEntity(), 'parent' => null],
				['created' => 'DESC']
			));

		$payload = $this->prepareHalCollection($paginator, 'process/version/detail/json');

		return new HalJsonModel([
			'payload' => $payload,
		]);
	}

	/**
	 * @return JsonViewModel
	 */
	public function commentAction()
	{
		$e	  = new \MA\Entity\Comment\Version;
		$em   = $this->getEntityManager();
		$form = $this->getServiceLocator()
			->get('FormElementManager')
			->get(\MA\Form\CommentForm::class);

		$e->setVersion($this->getEntity());
		$form->setHydrator(new DoctrineHydrator($em));
		$form->setAttribute('action', $this->url()->fromRoute(null, [], [], true));
		$form->bind($e);

		if ($this->getRequest()->isPost()) {
			$form->setData(Json::decode($this->getRequest()->getContent(), Json::TYPE_ARRAY));
			if ($form->isValid()) {

				$this->triggerService(\Base\Service\AbstractService::EVENT_CREATE, $e);

				$em->persist($e);
				$em->flush();
				$payload = [
					'payload' => $this->prepareHalEntity($e, "process/comment/detail/json")
				];
			}
			else {
				$ex = new \ZF\ApiProblem\Exception\DomainException('Unprocessable entity', 422);
				$ex->setAdditionalDetails(['errors' => $form->getMessages()]);
				throw $ex;
			}
		}

		return new HalJsonModel($payload);
	}
}
