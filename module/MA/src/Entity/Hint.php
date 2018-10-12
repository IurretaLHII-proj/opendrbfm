<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint")
 */
class Hint implements 
	//ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
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
	 * @ORM\Column(type="string")
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
	 * @var HintInterface[]
	 * @ORM\ManyToMany(
	 *	targetEntity = "MA\Entity\Hint",
	 *	mappedBy	 = "parents",
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $children;

	/**
	 * @var HintInterface[]
	 * @ORM\ManyToMany(
	 *	targetEntity = "MA\Entity\Hint",
	 *	inversedBy = "children"
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
	 */
	protected $parents;

	/**
	 * @var int 
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @var int 
	 * @ORM\Column(type="datetime")
	 */
	protected $updated;

	/**
	 * @return
	 */
	public function __construct()
	{
		$this->created  = new DateTime;
		$this->updated  = new DateTime;
		$this->parents  = new ArrayCollection;
		$this->children = new ArrayCollection;
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
     * @return string.
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Set text.
     *
     * @param string text the value to set.
     * @return Hint.
     */
    public function setText($text)
    {
        $this->text = $text;
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
     * Get process.
     *
	 * @return ProcessInterface
	 */
	public function getProcess()
	{
		return $this->getStage()->getProcess();
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
     * @return HintInterface.
     */
    public function getParents()
    {
        return $this->parents;
    }
    
    /**
     * Set parents.
     *
     * @param HintInterface parents the value to set.
     * @return HintInterface.
     */
    public function setParents($parents)
    {
        $this->parents = $parents;
        return $this;
    }
    
    /**
     * Get children.
     *
     * @return HintInterface.
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Set children.
     *
     * @param HintInterface children the value to set.
     * @return HintInterface.
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
    
    /**
     * Add child.
     *
     * @param HintInterface child the value to set.
     * @return Stage.
     */
    public function addChild(HintInterface $child)
    {
		$this->getChildren()->add($child);
        return $this;
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
		//echo '<pre>'; var_dump($this->getText() .' != '.$hint->getText()); echo '</pre>';

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
     * Add parent.
     *
     * @param HintInterface parent the value to set.
     * @return Stage.
     */
    public function addParent(HintInterface $parent)
    {
		$this->getParents()->add($parent);
        return $this;
    }
    
    /**
     * Add parents.
     *
     * @param HintInterface[] parents the value to set.
     * @return Stage.
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
     * @param HintInterface parent the value to set.
     * @return Stage.
     */
    public function removeParent(HintInterface $parent)
    {
		$this->getParents()->removeElement($parent);
        return $this;
    }
    
    /**
     * Add parents.
     *
     * @param HintInterface[] parents the value to set.
     * @return Stage.
     */
    public function removeParents($parents)
    {
		foreach ($parents as $parent) {
			$this->removeParent($parent);
		}

        return $this;
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
		return static::class;
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
				'rel'   	  => 'edit',
				//'privilege'   => 'edit',
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				//'privilege'   => 'edit',
				'route' => [
				    'name'    => 'process/hint/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
		];
	}
}
