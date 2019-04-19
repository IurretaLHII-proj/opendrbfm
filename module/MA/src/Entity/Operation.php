<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_op")
 */
class Operation implements 
	JsonSerializable,
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	OperationInterface 
{
	/**
	 * @var int 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="text")
	 */
	protected $name;

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
	 *	targetEntity = "MA\Entity\OperationType",
	 *	inversedBy	 = "operations",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "type_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $type;

	/**
	 * @var User
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "operations",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

	/**
	 * @var StageInterface[]
	 * @ORM\ManyToMany(
     *  targetEntity="MA\Entity\Stage",
	 *	mappedBy	 = "operations",
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $stages;

	/**
	 * @var OperationInterface[]
	 * @ORM\ManyToMany(
	 *	targetEntity = "MA\Entity\Operation",
	 *	mappedBy	 = "parents",
	 *	cascade 	 = {"persist"}
	 * )
	 * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $children;

	/**
	 * @var OperationInterface[]
	 * @ORM\ManyToMany(
	 *	targetEntity = "MA\Entity\Operation",
	 *	inversedBy = "children"
	 * )
	 * @ORM\JoinTable(
	 *	name = "process_op_rel",
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
	 * @var StageInterface[]
	 * @ORM\OneToMany(
     *  targetEntity="MA\Entity\HintType",
	 *	mappedBy	 = "operation",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $hints;

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
		$this->stages   = new ArrayCollection;
		$this->hints    = new ArrayCollection;
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
     * @return Operation.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }
    
    /**
     * Get name.
     *
     * @return string.
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Get longName.
     *
     * @return string.
     */
    public function getLongName()
    {
        return sprintf("%s. %s", (string) $this->getType(), $this->getName());
    }
    
    /**
     * Set name.
     *
     * @param string name the value to set.
     * @return Operation.
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * Get stages.
     *
     * @return StageInterface[].
     */
    public function getStages()
    {
        return $this->stages;
    }
    
    /**
     * Set stages.
     *
     * @param StageInterface[] stages the value to set.
     * @return OperationInterface.
     */
    public function setStages($stages)
    {
        $this->stages = $stages;
        return $this;
    }
    
    /**
     * Get parents.
     *
     * @return OperationInterface.
     */
    public function getParents()
    {
        return $this->parents;
    }
    
    /**
     * Set parents.
     *
     * @param OperationInterface[] parents the value to set.
     * @return OperationInterface.
     */
    public function setParents($parents)
    {
        $this->parents = $parents;
        return $this;
    }
    
    /**
     * Get children.
     *
     * @return OperationInterface.
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Set children.
     *
     * @param OperationInterface[] children the value to set.
     * @return OperationInterface.
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }
    
    /**
     * Add parent.
     *
     * @param OperationInterface child the value to set.
     * @return OperationInterface.
     */
    public function addChild(OperationInterface $child)
    {
		$child->getParents()->add($this);
		$this->getChildren()->add($child);
        return $this;
    }
    
    /**
     * Add children.
     *
     * @param OperationInterface[] children the value to set.
     * @return OperationInterface.
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
     * @param OperationInterface child the value to set.
     * @return OperationInterface.
     */
    public function removeChild(OperationInterface $child)
    {
		$child->getParents()->removeElement($this);
		$this->getChildren()->removeElement($child);
        return $this;
    }
    
    /**
     * Add children.
     *
     * @param OperationInterface[] children the value to set.
     * @return OperationInterface.
     */
    public function removeChildren($children)
    {
		foreach ($children as $child) {
			$this->removeChild($child);
		}

        return $this;
    }
    
    /**
     * Get hints.
     *
     * @return HintTypeInterface[].
     */
    public function getHints()
    {
        return $this->hints;
    }
    
    /**
     * Set hints.
     *
     * @param HintTypeInterface[] hints the value to set.
     * @return OperationInterface.
     */
    public function setHints($hints)
    {
        $this->hints = $hints;
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
     * Get type.
     *
     * @return OperationType.
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set type.
     *
     * @param OperationType type the value to set.
     * @return Operation.
     */
    public function setType(OperationType $type)
    {
        $this->type = $type;
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
     * @return Operation.
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
     * @return Operation.
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
     * @return Operation.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @ORM\PreUpdate
	 * @return Operation
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
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getLongName();
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		return [
			'id' 		  => $this->getId(),
			'name' 		  => $this->getName(),
			'longName' 	  => $this->getLongName(),
			'owner'		  => $this->getUser(),
			'description' => $this->getDescription(),
			'children' 	  => new \ZF\Hal\Collection($this->getChildren()),
			'updated'	  => $this->getUpdated(),
			'created'	  => $this->getCreated(),
		];
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
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/operation/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => $this->getParents()->count() ? false : 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/operation/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'hint',
				'privilege'   => 'hint',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/operation/detail/json',
				    'params'  => ['action' => 'hint', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'hints',
				'privilege'   => 'hints',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/operation/detail/json',
				    'params'  => ['action' => 'hints', 'id' => $this->getId()],
				],
			],
		];
	}
}
