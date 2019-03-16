<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint_context")
 */
class HintContext implements 
	JsonSerializable,
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	CommentProviderInterface,
	HintContextInterface 
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
     *     inversedBy="contexts"
     * )
     * @ORM\JoinColumn(
     *     name = "hint_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $hint;

	/**
	 * @var HintContextInterface[]
	 * @ORM\ManyToMany(
	 *	targetEntity = "MA\Entity\HintContext",
	 *	inversedBy = "children",
	 *	cascade 	 = {"persist"}
	 * )
	 * @ORM\JoinTable(
	 *	name = "process_hint_rel",
	 *	joinColumns = {
	 *		@ORM\JoinColumn(
	 *			name = "parent_id",
	 *			referencedColumnName = "id",
	 *		)
	 *	},
	 *	inverseJoinColumns = {
	 *		@ORM\JoinColumn(
	 *			name = "child_id",
	 *			referencedColumnName = "id",
	 *		)
	 *	}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $parents;

	/**
	 * @var HintContextInterface[]
	 * @ORM\ManyToMany(
	 *	targetEntity = "MA\Entity\HintContext",
	 *	mappedBy	 = "parents",
	 *	cascade 	 = {"persist"}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $children;

	/**
	 * @var Note\ContextReason[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\ContextReason",
	 *	mappedBy	 = "context",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $reasons;

	/**
	 * @var Note\ContextInfluence[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\ContextInfluence",
	 *	mappedBy	 = "context",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $influences;

	/**
	 * @var SimulationInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Simulation",
	 *	mappedBy	 = "context",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $simulations;

	/**
	 * @var CommentInterface[]
	 *	targetEntity = "MA\Entity\Comment\HintContext",
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
		$this->parents     = new ArrayCollection;
		$this->children    = new ArrayCollection;
		$this->comments    = new ArrayCollection;
		$this->reasons     = new ArrayCollection;
		$this->influences  = new ArrayCollection;
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
     * Get parents.
     *
     * @return HintContextInterface[].
     */
    public function getParents()
    {
        return $this->parents;
    }
    
    /**
     * Set parents.
     *
     * @param HintContextInterface[] parents the value to set.
     * @return HintContextInterface.
     */
    public function setParents($parents)
    {
        $this->parents = $parents;
        return $this;
    }
    
    /**
     * Get children.
     *
     * @return HintContextInterface.
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Set children.
     *
     * @param HintContextInterface[] children the value to set.
     * @return HintContextInterface.
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
    
    /**
     * Add children.
     *
     * @param HintContextInterface[] children the value to set.
     * @return HintContextInterface.
     */
    public function addChildren($children)
    {
		foreach ($children as $child) {
			$this->addChild($child);
		}

        return $this;
    }
    
    /**
     * Add child.
     *
     * @param HintContextInterface child the value to set.
     * @return HintContextInterface.
     */
    public function addChild(HintContextInterface $child)
    {
		$child->getParents()->add($this);
		$this->getChildren()->add($child);
        return $this;
    }
    
    /**
     * Remove children.
     *
     * @param HintContextInterface[] children the value to set.
     * @return HintContextInterface.
     */
    public function removeChildren($children)
    {
		foreach ($children as $child) {
			$this->removeChild($child);
		}

        return $this;
    }
    
    /**
     * Add child.
     *
     * @param HintContextInterface child the value to set.
     * @return HintContextInterface.
     */
    public function removeChild(HintContextInterface $child)
    {
		$this->getChildren()->removeElement($child);
        return $this;
    }
    
    /**
     * Add parent.
     *
     * @param HintContextInterface parent the value to set.
     * @return HintContextInterface.
     */
    public function addParent(HintContextInterface $parent)
    {
		$this->getParents()->add($parent);
        return $this;
    }
    
    /**
     * Add parents.
     *
     * @param HintContextInterface[] parents the value to set.
     * @return HintContextInterface.
     */
    public function addParents($parents)
    {
		foreach ($parents as $parent) {
			$this->addParent($parent);
		}

        return $this;
    }
    
    /**
     * Add parent.
     *
     * @param HintContextInterface parent the value to set.
     * @return HintContextInterface.
     */
    public function removeParent(HintContextInterface $parent)
    {
		$this->getParents()->removeElement($parent);
        return $this;
    }
    
    /**
     * Add parents.
     *
     * @param HintContextInterface[] parents the value to set.
     * @return HintContextInterface.
     */
    public function removeParents($parents)
    {
		foreach ($parents as $parent) {
			$this->removeParent($parent);
		}

        return $this;
    }

	/**
	 * todo
	 * @param HintContextInterface hint
	 * @return bool 
	 */
	public function hasParent(HintContextInterface $hint)
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
	 * todo
	 * @param HintContextInterface hint
	 * @return bool 
	 */
	public function hasChild(HintContextInterface $hint)
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
     * Get reasons.
     *
     * @return Note\ContextReason[].
     */
    public function getReasons()
    {
        return $this->reasons;
    }
    
    /**
     * Set reasons.
     *
     * @param Note\ContextReason[] reasons the value to set.
     * @return HintContextInterface.
     */
    public function setReasons($reasons)
    {
        $this->reasons = $reasons;
        return $this;
    }
    
    /**
     * Add reason.
     *
     * @param Note\ContextReason reason the value to set.
     * @return HintContextInterface.
     */
    public function addReason(Note\ContextReason $reason)
    {
		$reason->setContext($this);
		$this->getReasons()->add($reason);
        return $this;
    }
    
    /**
     * Add reasons.
     *
     * @param Note\ContextReason[] reasons the value to set.
     * @return HintContextInterface.
     */
    public function addReasons($reasons)
    {
		foreach ($reasons as $reason) {
			$this->addReason($reason);
		}

        return $this;
    }
    
    /**
     * Add reason.
     *
     * @param Note\ContextReason reason the value to set.
     * @return HintContextInterface.
     */
    public function removeReason(Note\ContextReason $reason)
    {
		$reason->setContext();
		$this->getReasons()->removeElement($reason);
        return $this;
    }
    
    /**
     * Add reasons.
     *
     * @param Note\ContextReason[] reasons the value to set.
     * @return HintContextInterface.
     */
    public function removeReasons($reasons)
    {
		foreach ($reasons as $reason) {
			$this->removeReason($reason);
		}

        return $this;
    }

    /**
     * Get influences.
     *
     * @return Note\ContextInfluence[].
     */
    public function getInfluences()
    {
        return $this->influences;
    }
    
    /**
     * Set influences.
     *
     * @param Note\ContextInfluence[] influences the value to set.
     * @return HintContext.
     */
    public function setInfluences($influences)
    {
        $this->influences = $influences;
        return $this;
    }
    
    /**
     * Add influence.
     *
     * @param Note\ContextInfluence influence the value to set.
     * @return HintContext.
     */
    public function addInfluence(Note\ContextInfluence $influence)
    {
		$influence->setContext($this);
		$this->getInfluences()->add($influence);
        return $this;
    }
    
    /**
     * Add influences.
     *
     * @param Note\ContextInfluence[] influences the value to set.
     * @return HintContext.
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
     * @param Note\ContextInfluence influence the value to set.
     * @return HintContext.
     */
    public function removeInfluence(Note\ContextInfluence $influence)
    {
		$influence->setContext();
		$this->getInfluences()->removeElement($influence);
        return $this;
    }
    
    /**
     * Add influences.
     *
     * @param Note\ContextInfluence[] influences the value to set.
     * @return HintContext.
     */
    public function removeInfluences($influences)
    {
		foreach ($influences as $influence) {
			$this->removeInfluence($influence);
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
     * @return HintContext.
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
     * @return HintContext.
     */
    public function addSimulation($simulation)
    {
		$simulation->setContext($this);
        $this->getSimulations()->add($simulation);
        return $this;
    }
    
    /**
     * Get comments.
     *
     * @return HintContextInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return HintContextInterface.
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

	public function getPreviouses()
	{
		return $this->getParents()->map(function($item) { return new HintContextRel($item); });
	}

	public function getNexts()
	{
		return $this->getChildren()->map(function($item) { return new HintContextRel($item); });
	}

    /**
	 * TODO
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            'id'       	   => $this->getId(),
			'hint'	   	   => $this->getHint(),
			'owner'	   	   => $this->getUser(),
            'created'  	   => $this->getCreated(),
			'commentCount' => $this->getCommentCount(),
			'reasons' 	   => new \ZF\Hal\Collection($this->getReasons()),
			'influences'   => new \ZF\Hal\Collection($this->getInfluences()),
			//'parents'    	 => new \ZF\Hal\Collection($this->getParents()),
			//'children'   	 => new \ZF\Hal\Collection($this->getChildren()),
			'parents'      => new \ZF\Hal\Collection($this->getPreviouses()),
			'children'     => new \ZF\Hal\Collection($this->getNexts()),
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
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'simulate',
				'privilege'   => 'simulate',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/context/detail/json',
				    'params'  => ['action' => 'simulate', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/context/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'reason',
				'privilege'   => 'reason',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/context/detail/json',
				    'params'  => ['action' => 'reason', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'influence',
				'privilege'   => 'influence',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/context/detail/json',
				    'params'  => ['action' => 'influence', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'edit',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/context/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/context/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/context/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
