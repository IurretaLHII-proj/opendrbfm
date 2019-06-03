<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint_type")
 */
class HintType implements 
	ResourceInterface, 
	JsonSerializable,
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	//\Base\Hal\LinkPrepareAware,
	HintTypeInterface 
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
	 * @var int
	 * @ORM\Column(type="integer", name="h_count", options={"default":0})
	 */
	protected $errorCount = 0;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="title")
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
	 * @var OperationInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Operation",
     *     inversedBy="hints"
     * )
     * @ORM\JoinColumn(
     *     name = "op_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $operation;

	/**
	 * @var HintInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Hint",
	 *	mappedBy	 = "type",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"priority" = "DESC"})
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
		$this->hints   = new ArrayCollection;
		$this->created = new DateTime;
		$this->updated = new DateTime;
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
     * @return HintType.
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
     * @return HintType.
     */
    public function setPriority($priority)
    {
        $this->priority = (int) $priority;
        return $this;
    }
    
    /**
     * Get errorCount.
     *
     * @return int.
     */
    public function getErrorCount()
    {
        return $this->errorCount;
    }
    
    /**
     * Set errorCount.
     *
     * @param int errorCount the value to set.
     * @return HintType.
     */
    public function setErrorCount($errorCount)
    {
        $this->errorCount = (int) $errorCount;
        return $this;
    }

	/**
	 * @return HintTypeInterface 
	 */
	public function increaseErrorCount()
	{
		$this->errorCount++;
		return $this;
	}

	/**
	 * @return HintTypeInterface 
	 */
	public function decreaseErrorCount()
	{
		if ($this->errorCount > 0) {
			$this->errorCount--;
		}
		return $this;
	}
    
    /**
     * Get name.
     *
     * @return string.
     */
    public function getTitle()
    {
        return $this->getName();
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
     * Set name.
     *
     * @param string name the value to set.
     * @return HintType.
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
    
    /**
     * Get operation.
     *
     * @return OperationInterface.
     */
    public function getOperation()
    {
        return $this->operation;
    }
    
    /**
     * Set operation.
     *
     * @param OperationInterface op the value to set.
     * @return HintType.
     */
    public function setOperation(OperationInterface $op)
    {
        $this->operation = $op;
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
     * Get hints.
     *
     * @return HintInterface[].
     */
    public function getHints()
    {
        return $this->hints;
    }
    
    /**
     * Set hints.
     *
     * @param HintInterface[] hints the value to set.
     * @return HintType.
     */
    public function setHints($hints)
    {
        $this->hints = $hints;
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
     * @return HintType.
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
     * @return HintType.
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
     * @return HintType.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @ORM\PreUpdate
	 * @return HintType
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
			case $this->priority >= 5:
				return 'danger';
			case $this->priority >= 4:
				return 'coral';
			case $this->priority >= 3:
				return 'light-salmon';
			case $this->priority >= 2:
				return 'warning';
			case $this->priority >= 1:
				return 'success';
			case $this->priority >= 0:
				return 'light-green';
			default:
				return 'light';
		}
	}

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            'id'          => $this->getId(),
            'name'        => $this->getName(),
			'color'		  => $this->getColor(),
            'priority' 	  => $this->getPriority(),
            'description' => $this->getDescription(),
			'operation'	  => $this->getOperation(),
			'owner'		  => $this->getUser(),
            'created'     => $this->getCreated(),
        );
    }

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getName();
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
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'edit',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/type/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'hints',
				'privilege'   => 'hints',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/type/detail/json',
				    'params'  => ['action' => 'hints', 'id' => $this->getId()],
				],
			],
		];
	}
}

