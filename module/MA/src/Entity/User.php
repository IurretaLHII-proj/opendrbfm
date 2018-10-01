<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Role\RoleInterface,
	Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use User\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser implements
	JsonSerializable,
	UserInterface
	//LinkProvider
{
	/**
	 * @var string
	 * @ORM\Column(type="string", options={"default":"user"})
	 */
	protected $role = "user";

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
	 * @param void
	 */
	public function __construct()
	{
		$this->created  = new DateTime;
		$this->updated  = new DateTime;
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
	public function getRoleId()
	{
		return $this->getRole();
	}
    
    /**
     * Get role.
     *
     * @return string.
     */
    public function getRole()
    {
        return $this->role;
    }
    
    /**
     * Set role.
     *
     * @param string role the value to set.
     * @return User.
     */
    public function setRole($role)
    {
        $this->role = (string) $role;
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
     * @return User.
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
     * @return User.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @ORM\PreUpdate
	 * @return Issue
	 */
	public function preUpdate()
	{
		$this->setUpdated(new DateTime);
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		return [
			'id' => $this->getId(),
			'name' => $this->getName(),
		];
	}
}
