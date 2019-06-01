<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use DateTime;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass = "MA\Doctrine\Repository\MachineRepository")
 * @ORM\Table(name="process_machine")
 */
class Machine implements 
	JsonSerializable,
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider
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
	 * @ORM\Column(type="string")
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
	 *	inversedBy	 = "plants",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

	/**
	 * @var int
	 * @ORM\Column(type="integer", options={"default":-0})
	 */
	protected $state = 0;

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
	 * @var ProcessInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Process",
	 *	mappedBy	 = "machine",
	 * )
	 */
	protected $processes;

	/**
	 * @return
	 */
	public function __construct()
	{
		$this->created   = new DateTime;
		$this->updated   = new DateTime;
		$this->processes = new ArrayCollection;
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
     * @return Machine.
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
     * Set name.
     *
     * @param string name the value to set.
     * @return Machine.
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
    
    /**
     * Set state.
     *
     * @param int state the value to set.
     * @return Machine.
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
     * @return Machine.
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
     * @return Machine.
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
     * @return Machine.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @param ProcessInterface[] $items
	 * @return Machine
	 */
	public function setProcesses($items) {
		$this->processes = $items;
		return $this;
	}

	/**
	 * @return ProcessInterface[]
	 */
	public function getProcesses()
	{
		return $this->processes;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
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
		return [
			'id' 		   => $this->getId(),
			'name' 		   => $this->getName(),
			'owner'		   => $this->getUser(),
			'state'		   => $this->getState(),
			'description'  => $this->getDescription(),
			'updated'	   => $this->getUpdated(),
			'created'	   => $this->getCreated(),
		];
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
				    'name'    => 'process/machine/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => $this->getProcesses()->count() ? false : 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/machine/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
		];
	}
}
