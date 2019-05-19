<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Role\RoleInterface,
	Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;
use BjyAuthorize\Provider\Role\ProviderInterface as RoleProviderInterface;
use User\Entity\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseUser implements
	JsonSerializable,
	RoleProviderInterface,
	ResourceInterface,
	UserInterface
	//LinkProvider
{
	/**
	 * @constants string
	 */
	const ROLE_SUPER = "super";

	/**
	 * @var string
	 * @ORM\Column(type="array")
	 */
	protected $roles = [];

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
     * Get roles.
     *
     * @return array.
     */
    public function getRoles()
    {
        return $this->roles;
    }
    
    /**
     * Set roles.
     *
     * @param array.
     * @return User.
     */
    public function setRoles(array $roles)
    {
		$this->roles = $roles;
		return $this;;
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
	public function toString()
	{
		return $this->getName();
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		return [
			'id' 	=> $this->getId(),
			'email' => $this->getEmail(),
			'name'  => $this->getName(),
			'roles' => $this->getRoles(),
		];
	}
}
