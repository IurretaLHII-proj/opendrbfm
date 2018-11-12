<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="note")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 * 	"hint-reason"     = "MA\Entity\Note\HintReason",
 * 	"hint-suggestion" = "MA\Entity\Note\HintSuggestion",
 * 	"hint-influence"  = "MA\Entity\Note\HintInfluence"
 * })
 */
abstract class AbstractNote implements 
	JsonSerializable, 
	\User\Entity\UserAwareInterface,
	NoteInterface
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
	 *	inversedBy	 = "notes",
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
	protected $text;

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
     * @return AbstractNote.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
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
     * @return AbstractNote.
     */
    public function setText($text)
    {
        $this->text = (string) $text;
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
     * @return AbstractNote.
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
			'text'		=> $this->getText(),
			'created'	=> $this->getCreated()->getTimestamp(),
		];
	
	}
}
