<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use DateTime;

/**
 * @ORM\MappedSuperclass
 */
abstract class AbstractAction implements 
	JsonSerializable, 
	\User\Entity\UserAwareInterface,
	ActionInterface
{
	/**
	 * @var int 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var UserInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "actions",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

	/**
	 * @var string 
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * @var string 
	 * @ORM\Column(type="array")
	 */
	protected $content = [];

	/**
	 * @var int 
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @return
	 */
	public function __construct()
	{
		$this->created = new DateTime;
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
     * @return AbstractAction.
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
     * @return AbstractAction.
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * Get content.
     *
     * @return int.
     */
    public function getContent()
    {
        return $this->content;
    }
    
    /**
     * Set content.
     *
     * @param int content the value to set.
     * @return AbstractAction.
     */
    public function setContent($content)
    {
        $this->content = (array) $content;
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
     * @return AbstractAction.
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
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
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		return [
			'id'		=> $this->getId(),
			'owner'		=> $this->getUser(),
			'class'		=> $this->getType(),
			'name'		=> $this->getName(),
			'source'	=> $this->getSource(),
			'content'	=> $this->getContent(),
			'created'	=> $this->getCreated(),
		];
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return get_class($this);
	}

	/**
	 * @return mixed
	 */
	abstract public function getSource();
}
