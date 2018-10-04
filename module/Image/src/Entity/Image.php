<?php

namespace Image\Entity;

use Zend\Permissions\Acl\Role\RoleInterface,
	Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

abstract class Image implements 
	JsonSerializable,
 	////ResourceInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	\User\Entity\UserAwareInterface,
 	ImageInterface
{
	/**
	 * @var int
	 */
    protected $id;

	/**
	 * @var string 
	 */
    protected $name;

	/**
	 * @var string 
	 */
    protected $type;

	/**
	 * @var int
	 */
    protected $size;

	/**
	 * @var string 
	 */
    protected $description;

	/**
	 * @var DateTime 
	 */
    protected $created;

	/**
	 * @var \User\Entity\UserInterface 
	 */
    protected $user;

 	/**
     * @return void
     */
    public function __construct()
    {
        $this->created = new DateTime;
    }

    /**
     * @param int $id
     * @return Image
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     * @return Image
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $size
     * @return Image
     */
    public function setSize($size)
    {
        $this->size = (int) $size;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param string $type
     * @return Image
     */
    public function setType($type)
    {
        $this->type = (string) $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $description
     * @return Image
     */
    public function setDescription($description = null)
    {
        $this->description = $description ? (string) $description : null;
        return $this;
    }

    /**
     * @return int
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param DateTime $created
     * @return Image
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param UserInterface|null $user
     * @return Image
     */
    public function setUser(\User\Entity\UserInterface $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            'id'          => $this->getId(),
            'name'        => $this->getName(),
            'type'    	  => $this->getType(),
            'size'    	  => $this->getSize(),
            'description' => $this->getDescription(),
            'created'     => $this->getCreated()->getTimestamp(),
        );
    }
}
