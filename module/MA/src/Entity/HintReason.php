<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint_reason")
 */
class HintReason implements 
	HintReasonInterface,
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
	 * @var HintInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Hint",
     *     inversedBy="reasons"
     * )
     * @ORM\JoinColumn(
     *     name = "hint_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $hint;

	/**
	 * @var HintInfluenceInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\HintInfluence",
	 *	mappedBy	 = "reason",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $influences;

	/**
	 * @var HintRelationInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\HintRelation",
	 *	mappedBy	 = "source",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $relations;

	/**
	 * @var Note\HintReason[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\HintReason",
	 *	mappedBy	 = "reason",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $notes;

	/**
	 * @var CommentInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Comment\HintReason",
	 *	mappedBy	 = "source",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $comments;

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
		$this->notes	   = new ArrayCollection;
		$this->relations   = new ArrayCollection;
		$this->comments    = new ArrayCollection;
		$this->influences  = new ArrayCollection;
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
     * Get hint.
     *
     * @return Hintnterface.
     */
    public function getHint()
    {
        return $this->hint;
    }
    
    /**
     * Set hint.
     *
     * @param HintInterface hint the value to set.
     * @return HintContext.
     */
    public function setHint(HintInterface $hint)
    {
        $this->hint = $hint;
        return $this;
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
     * @return HintContext.
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
     * @return HintReasonInterface.
     */
    public function addRelation(HintRelationInterface $relation)
    {
		$relation->setSource($this);
		$this->getRelations()->add($relation);
        return $this;
    }
    
    /**
     * Add relations.
     *
     * @param HintRelationInterface[] relations the value to set.
     * @return HintReasonInterface.
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
     * @return HintReasonInterface.
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
     * @return HintReasonInterface.
     */
    public function removeRelations($relations)
    {
		foreach ($relations as $relation) {
			$this->removeRelation($relation);
		}

        return $this;
    }
    
    /**
     * Get influences.
     *
     * @return HintInfluenceInterface[].
     */
    public function getInfluences()
    {
        return $this->influences;
    }
    
    /**
     * Set influences.
     *
     * @param HintInfluenceInterface[] influences the value to set.
     * @return HintReasonInterface.
     */
    public function setInfluences($influences)
    {
        $this->influences = $influences;
        return $this;
    }
    
    /**
     * Add influences.
     *
     * @param HintInfluenceInterface[] influences the value to set.
     * @return HintReasonInterface.
     */
    public function addInfluences($influences)
    {
		foreach ($influences as $influence) {
			$this->addInfluence($influence);
		}

        return $this;
    }
    
    /**
     * Add influence.
     *
     * @param HintInfluenceInterface influence the value to set.
     * @return HintReasonInterface.
     */
    public function addInfluence(HintInfluenceInterface $influence)
    {
		$influence->setReason($this);
		$this->getInfluences()->add($influence);
        return $this;
    }
    
    /**
     * Remove influences.
     *
     * @param HintInfluenceInterface[] influences the value to set.
     * @return HintReasonInterface.
     */
    public function removeInfluences($influences)
    {
		foreach ($influences as $influence) {
			$this->removeInfluence($influence);
		}

        return $this;
    }
    
    /**
     * Add influence.
     *
     * @param HintInfluenceInterface influence the value to set.
     * @return HintReasonInterface.
     */
    public function removeInfluence(HintContextInterface $influence)
    {
		$this->getInfluences()->removeElement($influence);
        return $this;
    }
    
    /**
     * Get notes.
     *
     * @return Note\HintReason[].
     */
    public function getNotes()
    {
        return $this->notes;
    }
    
    /**
     * Set notes.
     *
     * @param Note\HintReason[] notes the value to set.
     * @return HintReasonInterface.
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    
    /**
     * Add note.
     *
     * @param Note\HintReason note the value to set.
     * @return HintReasonInterface.
     */
    public function addNote(Note\HintReason $note)
    {
		$note->setReason($this);
		$this->getNotes()->add($note);
        return $this;
    }
    
    /**
     * Add notes.
     *
     * @param Note\HintReason[] notes the value to set.
     * @return HintReasonInterface.
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
     * @param Note\HintReason note the value to set.
     * @return HintReasonInterface.
     */
    public function removeNote(Note\HintReason $note)
    {
		$note->setReason();
		$this->getNotes()->removeElement($note);
        return $this;
    }
    
    /**
     * Add notes.
     *
     * @param Note\HintReason[] notes the value to set.
     * @return HintReasonInterface.
     */
    public function removeNotes($notes)
    {
		foreach ($notes as $note) {
			$this->removeNote($note);
		}

        return $this;
    }
    
    /**
     * Get comments.
     *
     * @return HintReasonInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return HintReasonInterface.
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
			'influences'   => new \ZF\Hal\Collection($this->getInfluences()),
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
		$this->influences   = new ArrayCollection;
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
				    'name'    => 'process/hint/reason/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'note',
				'privilege'   => 'note',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/reason/detail/json',
				    'params'  => ['action' => 'note', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'relation',
				'privilege'   => 'relation',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/reason/detail/json',
				    'params'  => ['action' => 'relation', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'influence',
				'privilege'   => 'influence',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/reason/detail/json',
				    'params'  => ['action' => 'influence', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/reason/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/reason/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
