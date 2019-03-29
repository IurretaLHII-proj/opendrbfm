<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint_relation")
 */
class HintRelation implements 
	ResourceInterface, 
	JsonSerializable,
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	CommentProviderInterface,
	HintRelationInterface 
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
	 *	inversedBy	 = "relations",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

    /**
	 * @var HintContextInterface
     * @ORM\ManyToOne(
     *     targetEntity = "MA\Entity\HintReason",
     *     inversedBy   = "relations",
	 *	   cascade 	 = {"persist"}
     * )
     * @ORM\JoinColumn(
     *     name = "src_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $source;

    /**
	 * @var HintContextInterface
     * @ORM\ManyToOne(
     *     targetEntity = "MA\Entity\HintInfluence",
	 *     inversedBy   = "relations",
	 *	   cascade 	 = {"persist"}
     * )
     * @ORM\JoinColumn(
     *     name = "ctx_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $relation;

	/**
	 * @var CommentInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Comment\HintRelation",
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
		$this->comments    = new ArrayCollection;
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
     * Get source.
     *
     * @return HintContextInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param HintContextInterface source the value to set.
     * @return Hint.
     */
    public function setSource(HintReasonInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get relation.
     *
     * @return HintContextInterface.
     */
    public function getRelation()
    {
        return $this->relation;
    }
    
    /**
     * Set relation.
     *
     * @param HintContextInterface relation the value to set.
     * @return Hint.
     */
    public function setRelation(HintInfluenceInterface $relation)
    {
        $this->relation = $relation;
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
     * Get comments.
     *
     * @return HintInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return HintInterface.
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
        return array(
            'id'           => $this->getId(),
			'commentCount' => $this->getCommentCount(),
			'description'  => $this->getDescription(),
			'commentCount' => $this->getCommentCount(),
			'owner'		   => $this->getUser(),
            'created'      => $this->getCreated(),
			'source'	   => new HintReasonRel($this->getSource()),
			'relation'	   => new HintInfluenceRel($this->getRelation()),
        );
    }

	/**
	 * @inheritDoc
	 */
	public function __clone()
	{
		$this->id 		    = null;
		$this->commentCount = 0;
		$this->created      = new DateTime;
		$this->updated      = new DateTime;
		$this->comments     = new ArrayCollection;
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
				    'name'    => 'process/hint/relation/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/relation/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/relation/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/relation/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
