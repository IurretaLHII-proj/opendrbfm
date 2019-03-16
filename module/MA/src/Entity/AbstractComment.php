<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use User\Entity\UserAwareInterface;
use User\Entity\UserInterface;
use Base\Hal\LinkProvider;

/**
 * @ORM\Entity(repositoryClass = "MA\Doctrine\Repository\CommentRepository")
 * @ORM\Table(name="comment")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 * 	"version"     	= "MA\Entity\Comment\Version",
 * 	"stage"     	= "MA\Entity\Comment\Stage",
 * 	"hint"     		= "MA\Entity\Comment\Hint",
 * 	"context"     	= "MA\Entity\Comment\HintContext",
 * 	"note"     		= "MA\Entity\Comment\Note",
 * 	"simulation"	= "MA\Entity\Comment\Simulation",
 * })
 */
abstract class AbstractComment implements 
	JsonSerializable,
	ResourceInterface, 
	UserAwareInterface,
	LinkProvider,
	CommentProviderInterface
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
	protected $body;

	/**
	 * @var IUser
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "hintComments",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

	/**
	 * @var PostComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\AbstractComment",
	 *	inversedBy	 = "children",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "prt_id",
	 *	referencedColumnName = "id",
	 *	nullable = true
	 * )
	 */
	protected $parent;

	/**
	 * @var PostComment[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\AbstractComment",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @var int 
	 * @ORM\Column(type="datetime")
	 */
	protected $created;

	/**
	 * @var int
	 * @ORM\Column(type="integer", name="cmm_c", options={"default":0})
	 */
	protected $commentCount = 0;

	public function __construct()
	{
		$this->created  = new DateTime;
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
     * @return AbstractComment.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }
    
    /**
     * Get body.
     *
     * @return string.
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * Set body.
     *
     * @param string body the value to set.
     * @return AbstractComment.
     */
    public function setBody($body)
    {
        $this->body = (string) $body;
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
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getId();
	}
    
    /**
     * @return AbstractComment|null.
     *
     * @return AbstractPostComment.
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Set parent.
     *
     * @param AbstractComment parent the value to set.
     * @return AbstractComment.
     */
    public function setParent(AbstractComment $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * Get comments.
     *
     * @return AbstractComment[].
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Set comments.
     *
     * @param AbstractComment[] comments the value to set.
     * @return AbstractComment.
     */
    public function setChildren($comments)
    {
        $this->children = $comments;
        return $this;
    }
    
    /**
	 * @inheritDoc
     */
    public function getComments()
    {
        return $this->getChildren();
    }
    
    /**
	 * @inheritDoc
     */
    public function setComments($comments)
    {
        $this->setChildren($comments);
        return $this;
    }

    /**
     * Get source.
     *
     * @return mixed.
     */
    abstract public function getSource();
    
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
     * @return AbstractComment.
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
        return $this;
    }
    
    /**
     * Get commentCount.
     *
     * @return int.
     */
    public function getCommentCount()
    {
        return $this->commentCount;
    }
    
    /**
     * Set commentCount.
     *
     * @param int commentCount the value to set.
     * @return AbstractComment.
     */
    public function setCommentCount($commentCount)
    {
        $this->commentCount = (int) $commentCount;
        return $this;
    }

	/**
     * @return AbstractComment.
	 */
	public function increaseCommentCount()
	{
		$this->commentCount++;
		return $this;
	}

	/**
     * @return AbstractComment.
	 */
	public function decreaseCommentCount()
	{
		$this->commentCount--;
		return $this;
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
			'id'       	   => $this->getId(),
			'body'     	   => $this->getBody(),
			'owner'	   	   => $this->getUser(),
			'created'  	   => $this->getCreated(),
			'parent'   	   => $this->getParent(),
			'commentCount' => $this->getCommentCount(),
			'children' 	   => new \ZF\Hal\Collection($this->getChildren()),
		];
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   => 'reply',
				'route' => [
				    'name'    => 'process/comment/detail/json',
				    'params'  => ['action' => 'reply', 'id' => $this->getId()],
				    'options' => [ /* any options to pass to the router */ ],
				],
				'privilege' => 'reply',
			],
			[
				'rel'   => 'children',
				'route' => [
				    'name'    => 'process/comment/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				    'options' => [ /* any options to pass to the router */ ],
				],
				'privilege' => 'children',
			],
			//[
			//	'rel'   => 'comments',
			//	'route' => [
			//	    'name'    => 'user/post/comment/detail/json',
			//	    'params'  => ['action' => 'comments', 'id' => $this->getId()],
			//	],
			//	'privilege' => 'comments',
			//],
			//[
			//	'rel'   => 'voteUp',
			//	'route' => [
			//	    'name'    => 'user/post/comment/detail/json',
			//	    'params'  => ['action' => 'vote-up', 'id' => $this->getId()],
			//	],
			//	'privilege' => 'vote-up',
			//],
			//[
			//	'rel'   => 'voteDown',
			//	'route' => [
			//	    'name'    => 'user/post/comment/detail/json',
			//	    'params'  => ['action' => 'vote-down', 'id' => $this->getId()],
			//	],
			//	'privilege' => 'vote-up',
			//],
		];
	}
}
