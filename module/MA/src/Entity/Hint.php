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
	 * @var HintContextInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\HintContext",
	 *	mappedBy	 = "hint",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $contexts;

	/**
	 * @var int
	 * @ORM\Column(type="integer", options={"default":0})
	 */
	protected $state = 0;

	/**
	 * @var string 
	 * @ORM\Column(type="string", options={"nullable":true})
	 */
	protected $who;

	/**
	 * @var DateTime 
	 * @ORM\Column(type="datetime", name="whn", options={"nullable":true})
	 */
	protected $when;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="eff", options = {"nullable":true})
	 */
	protected $effect;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="prev", options = {"nullable":true})
	 */
	protected $prevention;

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
     * Set state.
     *
     * @param int state the value to set.
     * @return Hint.
     */
    public function setState($state)
    {
        $this->state = (int) $state;
        return $this;
    }
    
    /**
     * Get state.
     *
     * @return int.
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Get who.
     *
     * @return string.
     */
    public function getWho()
    {
        return $this->who;
    }
    
    /**
     * Set who.
     *
     * @param string who the value to set.
     * @return Hint.
     */
    public function setWho($who = null)
    {
        $this->who = $who ? (string) $who : $who;
        return $this;
    }
    
    /**
     * Get when.
     *
     * @return DateTime.
     */
    public function getWhen()
    {
        return $this->when;
    }
    
    /**
     * Set when.
     *
     * @param DateTime when the value to set.
     * @return Hint.
     */
    public function setWhen(DateTime $when = null)
    {
        $this->when = $when;
        return $this;
    }
    
    /**
     * Get effect.
     *
     * @return string|null.
     */
    public function getEffect()
    {
        return $this->effect;
    }
    
    /**
     * Set effect.
     *
     * @param strin|nullg effect the value to set.
     * @return Hint.
     */
    public function setEffect($effect = null)
    {
		$this->effect = $effect ? (string) $effect : $effect;
        return $this;
    }
    
    /**
     * Get prevention.
     *
     * @return string|null.
     */
    public function getPrevention()
    {
        return $this->prevention;
    }
    
    /**
     * Set prevention.
     *
     * @param strin|nullg prevention the value to set.
     * @return Hint.
     */
    public function setPrevention($prevention = null)
    {
		$this->prevention = $prevention ? (string) $prevention : $prevention;
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
		foreach ($this->getContexts() as $context) {
			foreach ($context->getParents()->map(function($e){ return $e->getHint(); }) as $hint) {
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
		foreach ($this->getContexts() as $context) {
			foreach ($context->getChildren()->map(function($e){ return $e->getHint(); }) as $hint) {
				if (!$children->contains($hint)) $children->add($hint);
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
     * Get contexts.
     *
     * @return Context[].
     */
    public function getContexts()
    {
        return $this->contexts;
    }
    
    /**
     * Set contexts.
     *
     * @param Context[] contexts the value to set.
     * @return Hint.
     */
    public function setContexts($contexts)
    {
        $this->contexts = $contexts;
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
				'rel'   	  => 'context',
				'privilege'   => 'context',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'context', 'id' => $this->getId()],
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
