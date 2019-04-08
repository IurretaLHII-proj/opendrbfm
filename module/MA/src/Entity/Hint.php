<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint")
 */
class Hint implements 
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	CommentProviderInterface,
	HintInterface 
{
	/**
	 * @var int 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var int
	 * @ORM\Column(type="integer", name="prior", options={"default":1})
	 */
	protected $priority = 1;

	/**
	 * @var string 
	 * @ORM\Column(type="string", options = {"nullable":true})
	 */
	protected $text;

    /**
	 * @var string 
     * @ORM\Column(
     *    type="string",
     *    name="descr",
     *    options = {"nullable":true}
     * )
     */
    protected $description;

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
	 * @var StageInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Stage",
     *     inversedBy="hints"
     * )
     * @ORM\JoinColumn(
     *     name = "stg_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $stage;

    /**
	 * @var HintTypeInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\HintType",
     *     inversedBy="hints"
     * )
     * @ORM\JoinColumn(
     *     name = "type_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $type;

	/**
	 * @var HintReasonInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\HintReason",
	 *	mappedBy	 = "hint",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $reasons;

	/**
	 * @var CommentInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Comment\Hint",
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
		$this->created   = new DateTime;
		$this->updated   = new DateTime;
		$this->reasons   = new ArrayCollection;
		$this->comments  = new ArrayCollection;
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
     * Get priority.
     *
     * @return int.
     */
    public function getPriority()
    {
        return $this->priority;
    }
    
    /**
     * Set priority.
     *
     * @param int priority the value to set.
     * @return Hint.
     */
    public function setPriority($priority)
    {
        $this->priority = (int) $priority;
        return $this;
    }
    
    /**
     * Get text.
     *
     * @return string|null.
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Set text.
     *
     * @param string|null text the value to set.
     * @return Hint.
     */
    public function setText($text = null)
    {
        $this->text = $text ?  (string) $text : $text;
        return $this;
    }
    
    /**
     * Get stage.
     *
     * @return StageInterface.
     */
    public function getStage()
    {
        return $this->stage;
    }
    
    /**
     * Set stage.
     *
     * @param StageInterface stage the value to set.
     * @return Hint.
     */
    public function setStage(StageInterface $stage)
    {
        $this->stage = $stage;
        return $this;
    }

	/**
	 * @return OperationInterface
	 */
	public function getOperation()
	{
		return $this->getType()->getOperation();
	}
    
    /**
     * Get type.
     *
     * @return HintTypeInterface.
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set type.
     *
     * @param HintTypeInterface type the value to set.
     * @return Hint.
     */
    public function setType(HintTypeInterface $type)
    {
        $this->type = $type;
        return $this;
    }

	/**
     * Get process.
     *
	 * @return ProcessInterface
	 */
	public function getProcess()
	{
		return $this->getStage()->getProcess();
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getType()->getTitle();

		//return sprintf("Stage %d. %s", 
		//	$this->getStage()->getLevel(),
		//	$this->getType()->getTitle()
		//);
	}

	/**
	 * @param HintReasonInterface $reason
	 * @return HintInterface
	 */
	public function addReason(HintReasonInterface $reason)
	{
		$reason->setHint($this);
		$this->getReasons()->add($reason);
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
     * Get comments.
     *
     * @return HintInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return HintInterface.
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
     * Get parents.
     *
     * @return HintInterface.
     */
    public function getParents()
    {
		$parents = new ArrayCollection;
		foreach ($this->getReasons() as $reason) {
			foreach ($reason->getRelations()->map(function($r) { return $r->getRelation()->getHint(); }) as $hint) {
				if (!$parents->contains($hint)) $parents->add($hint);
			}	
		}
		return $parents;
    }
    
    /**
     * Get children.
     *
     * @return HintInterface.
     */
    public function getChildren()
    {
		$children = new ArrayCollection;
		foreach ($this->getReasons() as $reason) {
			foreach ($reason->getInfluences() as $infl) {
				foreach ($infl->getRelations()->map(function($r) { return $r->getSource()->getHint(); }) as $hint) {
					if (!$children->contains($hint)) $children->add($hint);
				}	
			}	
		}
		return $children;
    }

	/**
	 * @param HintInterface hint
	 * @return bool 
	 */
	public function hasParent(HintInterface $hint)
	{
		if ($this->getParents()->contains($hint) !== false) {
			return true;	
		}

		foreach ($this->getParents() as $parent) {
			if ($parent->hasParent($hint)) return true;
		}

		return false;
	}

	/**
	 * @param HintInterface hint
	 * @return bool 
	 */
	public function hasChild(HintInterface $hint)
	{
		if ($this->getChildren()->contains($hint) !== false) {
			return true;	
		}

		foreach ($this->getChildren() as $child) {
			if ($child->hasChild($hint)) return true;
		}

		return false;
	}
    
    /**
     * Get description.
     *
     * @return string.
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Set description.
     *
     * @param string descr the value to set.
     * @return Stage.
     */
    public function setDescription($descr = null)
    {
        $this->description = $descr ? (string) $descr : $descr;
        return $this;
    }
    
    /**
     * Get reasons.
     *
     * @return HintReasonInterface[].
     */
    public function getReasons()
    {
        return $this->reasons;
    }
    
    /**
     * Set reasons.
     *
     * @param HintReasonInterface[] reasons the value to set.
     * @return Hint.
     */
    public function setReasons($reasons)
    {
        $this->reasons = $reasons;
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
	 * @return string
	 */
	public function getColor()
	{
		switch (true) {
			case $this->priority >= 6:
				return 'danger';
			case $this->priority >= 4:
				return 'warning';
			case $this->priority >= 2:
				return 'success';
			case $this->priority >= 0:
				return 'secondary';
			default:
				return 'light';
		}
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getText();
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
            'id'          => $this->getId(),
            'text'        => $this->getText(),
            'priority' 	  => $this->getPriority(),
            'description' => $this->getDescription(),
            'created'     => $this->getCreated(),
        );
    }

	/**
	 * @inheritDoc
	 */
	public function __clone()
	{
		$this->id 		    = null;
		$this->commentCount = 0;
		$this->created      = new DateTime;
		$this->updated      = new DateTime;
		$this->comments     = new ArrayCollection;
	}

	/**
	 * @inheritDoc
	 */
	public function prepareLinks(\ZF\Hal\Link\LinkCollection $links)
	{
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'reason',
				'privilege'   => 'reason',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'reason', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'edit',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
