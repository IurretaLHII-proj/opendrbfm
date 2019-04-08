<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint_influence")
 */
class HintInfluence implements
   	HintInfluenceInterface,
	JsonSerializable,
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	CommentProviderInterface
{
	/**
	 * @var int 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var User
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "hints",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

	/**
	 * @var HintReasonInterface[]
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintReason",
	 *	inversedBy   = "influences",
	 *	cascade 	 = {"persist"}
	 * )
	 * @ORM\JoinColumn(
	 *	name= "rsn_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $reason;

	/**
	 * @var HintRelationInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\HintRelation",
	 *	mappedBy	 = "relation",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $relations;

	/**
	 * @var Note\HintInfluence[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\HintInfluence",
	 *	mappedBy	 = "influence",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $notes;

	/**
	 * @var CommentInterface[]
	 *	targetEntity = "MA\Entity\Comment\HintInfluence",
	 *	mappedBy	 = "source",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $comments;

	/**
	 * @var SimulationInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Simulation",
	 *	mappedBy	 = "influence",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $simulations;

	/**
	 * @var int
	 * @ORM\Column(type="integer", name="cmm_c", options={"default":0})
	 */
	protected $commentCount = 0;

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @var DateTime
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;

	/**
	 * @return
	 */
	public function __construct()
	{
		$this->created     = new DateTime;
		$this->updated     = new DateTime;
		$this->comments    = new ArrayCollection;
		$this->notes	   = new ArrayCollection;
		$this->relations   = new ArrayCollection;
		$this->simulations = new ArrayCollection;
	}
    
    /**
     * Get id.
     *
     * @return int.
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set id.
     *
     * @param int id the value to set.
     * @return Hint.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }
    
    /**
     * Get reason.
     *
	 * @inheritDoc
     */
    public function getReason()
    {
        return $this->reason;
    }
    
    /**
     * Set reason.
     *
	 * @inheritDoc
     */
    public function setReason(HintReasonInterface $reason)
    {
        $this->reason = $reason;
        return $this;
    }

	/**
	 * @return HintInterface
	 */
	public function getHint()
	{
		return $this->getReason()->getHint();
	}

	/**
	 * @return ProcessInterface
	 */
	public function getProcess()
	{
		return $this->getReason()->getProcess();
	}
    
    /**
     * Get user.
     *
	 * @inheritDoc
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
     * Set user.
     *
	 * @inheritDoc
     */
    public function setUser(\User\Entity\UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }
    
    /**
     * Get relations.
     *
     * @return HintRelation.
     */
    public function getRelations()
    {
        return $this->relations;
    }
    
    /**
     * Set relations.
     *
     * @param HintRelation[] relations the value to set.
     * @return HintInfluence.
     */
    public function setRelations($relations)
    {
        $this->relations = $relations;
        return $this;
    }
    
    /**
     * Add relation.
     *
     * @param HintRelationInterface relation the value to set.
     * @return HintInfluenceInterface.
     */
    public function addRelation(HintRelationInterface $relation)
    {
		$relation->setRelation($this);
		$this->getRelations()->add($relation);
        return $this;
    }
    
    /**
     * Add relations.
     *
     * @param HintRelationInterface[] relations the value to set.
     * @return HintInfluenceInterface.
     */
    public function addRelations($relations)
    {
		foreach ($relations as $relation) {
			$this->addRelation($relation);
		}

        return $this;
    }
    
    /**
     * Add relation.
     *
     * @param HintRelationInterface relation the value to set.
     * @return HintInfluenceInterface.
     */
    public function removeRelation(HintRelationInterface $relation)
    {
		$this->getRelations()->removeElement($relation);
        return $this;
    }
    
    /**
     * Add relations.
     *
     * @param HintRelationInterface[] relations the value to set.
     * @return HintInfluenceInterface.
     */
    public function removeRelations($relations)
    {
		foreach ($relations as $relation) {
			$this->removeRelation($relation);
		}

        return $this;
    }
    
    /**
     * Get notes.
     *
     * @return Note\HintInfluence[].
     */
    public function getNotes()
    {
        return $this->notes;
    }
    
    /**
     * Set notes.
     *
     * @param Note\HintInfluence[] notes the value to set.
     * @return HintInfluenceInterface.
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    
    /**
     * Add note.
     *
     * @param Note\HintInfluence note the value to set.
     * @return HintInfluenceInterface.
     */
    public function addNote(Note\HintInfluence $note)
    {
		//var_dump(spl_object_hash($this), spl_object_hash($note), $note->getText());
		$note->setInfluence($this);
		$this->getNotes()->add($note);
        return $this;
    }
    
    /**
     * Add notes.
     *
     * @param Note\HintInfluence[] notes the value to set.
     * @return HintInfluenceInterface.
     */
    public function addNotes($notes)
    {
		foreach ($notes as $note) {
			$this->addNote($note);
		}

        return $this;
    }
    
    /**
     * Add note.
     *
     * @param Note\HintInfluence note the value to set.
     * @return HintInfluenceInterface.
     */
    public function removeNote(Note\HintInfluence $note)
    {
		$note->setInfluence();
		$this->getNotes()->removeElement($note);
        return $this;
    }
    
    /**
     * Add notes.
     *
     * @param Note\HintInfluence[] notes the value to set.
     * @return HintInfluenceInterface.
     */
    public function removeNotes($notes)
    {
		foreach ($notes as $note) {
			$this->removeNote($note);
		}

        return $this;
    }
    
    /**
     * Get simulations.
     *
     * @return Simulation[].
     */
    public function getSimulations()
    {
        return $this->simulations;
    }
    
    /**
     * Set simulations.
     *
     * @param Simulation[] simulations the value to set.
     * @return HintInfluence.
     */
    public function setSimulations($simulations)
    {
        $this->simulations = $simulations;
        return $this;
    }
    
    /**
     * Add simulations.
     *
     * @param SimulationInterface simulation the value to set.
     * @return HintInfluence.
     */
    public function addSimulation(SimulationInterface $simulation)
    {
		$simulation->setInfluence($this);
        $this->getSimulations()->add($simulation);
        return $this;
    }
    
    /**
     * Add simulations.
     *
     * @param SimulationInterface simulation the value to set.
     * @return HintInfluence.
     */
    public function removeSimulation(SimulationInterface $simulation)
    {
		//$simulation->setInfluence();
        $this->getSimulations()->removeElement($simulation);
        return $this;
    }
    
    /**
     * Add simulations.
     *
     * @param SimulationInterface[] simulations the value to set.
     * @return HintInfluenceInterface.
     */
    public function addSimulations($simulations)
    {
		foreach ($simulations as $simulation) {
			$this->addSimulation($simulation);
		}

        return $this;
    }
    
    /**
     * Add simulations.
     *
     * @param SimulationInterface[] simulations the value to set.
     * @return HintInfluenceInterface.
     */
    public function removeSimulations($simulations)
    {
		foreach ($simulations as $simulation) {
			$this->removeSimulation($simulation);
		}

        return $this;
    }
    
    /**
     * Get comments.
     *
     * @return HintInfluenceInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return HintInfluenceInterface.
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }
    
    /**
	 * @inheritDoc
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }
    
    /**
	 * @inheritDoc
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = (int) $commentCount;
        return $this;
    }

	/**
	 * @inheritDoc
	 */
	public function increaseCommentCount()
	{
		$this->commentCount++;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function decreaseCommentCount()
	{
		$this->commentCount--;
		return $this;
	}

    /**
     * Get created.
     *
     * @return DateTime.
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    /**
     * Set created.
     *
     * @param DateTime created the value to set.
     * @return Hint.
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
        return $this;
    }
    
    /**
     * Get updated.
     *
     * @return DateTime.
     */
    public function getUpdated()
    {
        return $this->updated;
    }
    
    /**
     * Set updated.
     *
     * @param DateTime updated the value to set.
     * @return Hint.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @ORM\PreUpdate
	 * @return Hint
	 */
	public function preUpdate()
	{
		return $this->setUpdated(new DateTime);
	}

	/**
	 * @inheritDoc
	 */
	public function getResourceId()
	{
		return self::class;
	}

    /**
	 * TODO
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            'id'       	   => $this->getId(),
			'owner'	   	   => $this->getUser(),
            'created'  	   => $this->getCreated(),
			'commentCount' => $this->getCommentCount(),
			'notes' 	   => new \ZF\Hal\Collection($this->getNotes()),
			'relations'	   => new \ZF\Hal\Collection($this->getRelations()),
			'simulations'  => new \ZF\Hal\Collection($this->getSimulations()),
        );
    }

	/**
	 * TODO
	 * @inheritDoc
	 */
	public function __clone()
	{
		$this->id 		    = null;
		$this->created      = new DateTime;
		$this->updated      = new DateTime;
		$this->simulations	= new ArrayCollection;
		$this->notes	    = new ArrayCollection;
		$this->relations    = new ArrayCollection;
		$this->comments     = new ArrayCollection;
		$this->commentCount = 0;
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/influence/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'note',
				'privilege'   => 'note',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/influence/detail/json',
				    'params'  => ['action' => 'note', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'relation',
				'privilege'   => 'relation',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/influence/detail/json',
				    'params'  => ['action' => 'relation', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'simulation',
				'privilege'   => 'simulation',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/influence/detail/json',
				    'params'  => ['action' => 'simulation', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/influence/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/influence/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
