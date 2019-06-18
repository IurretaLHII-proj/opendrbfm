<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use DateTime;

/**
 * @ORM\Entity(repositoryClass = "MA\Doctrine\Repository\StageRepository")
 * @ORM\Table(name="process_stage")
 */
class Stage implements 
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	CommentProviderInterface,
	StageInterface 
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
	 * @ORM\Column(type="string", options="{nullable: true}")
	 */
	protected $body;

	/**
	 * @var User
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "stages",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

	/**
	 * @var VersionInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Version",
	 *	inversedBy	 = "stages",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "v_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $version;

	/**
	 * @var OperationInterface[]
	 * @ORM\ManyToMany(
	 *	targetEntity = "MA\Entity\Operation",
	 *	inversedBy = "stages"
	 * )
	 * @ORM\JoinTable(
	 *	name = "process_op_stg_rel",
	 *	joinColumns = {
	 *		@ORM\JoinColumn(
	 *			name = "op_id",
	 *			referencedColumnName = "id",
	 *		)
	 *	},
	 *	inverseJoinColumns = {
	 *		@ORM\JoinColumn(
	 *			name = "stg_id",
	 *			referencedColumnName = "id",
	 *		)
	 *	}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $operations;

	/**
	 * @var HintInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Hint",
	 *	mappedBy	 = "stage",
	 *	cascade = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"priority" = "DESC"})
	 */
	protected $hints;

	/**
	 * @var Image\IStage[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Image\IStage",
	 *	mappedBy	 = "source",
	 *	cascade = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $images;

	/**
	 * @var ActionInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Action\Stage",
	 *	mappedBy	 = "source",
	 *	cascade 	 = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $actions;

	/**
	 * @var CommentInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Comment\Stage",
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
	 * @var int 
	 * @ORM\Column(type="integer", name="ord", options={"default":0})
	 */
	protected $order = 0;


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
		$this->created    = new DateTime;
		$this->updated    = new DateTime;
		$this->operations = new ArrayCollection;
		$this->images     = new ArrayCollection;
		$this->hints	  = new ArrayCollection;
		$this->actions    = new ArrayCollection;
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
     * @return Stage.
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
     * @return Stage.
     */
    public function setBody($body = null)
    {
        $this->body = $body ? (string) $body : $body;
        return $this;
    }
    
    /**
     * Get process.
     *
     * @return ProcessInterface.
     */
    public function getProcess()
    {
        return $this->getVersion()->getProcess();
    }
    
    /**
     * Get version.
     *
     * @return VersionInterface.
     */
    public function getVersion()
    {
        return $this->version;
    }
    
    /**
     * Set version.
     *
     * @param VersionInterface version the value to set.
     * @return Stage.
     */
    public function setVersion(VersionInterface $version)
    {
        $this->version = $version;
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
     * Get operations.
     *
     * @return StageInterface[].
     */
    public function getOperations()
    {
        return $this->operations;
    }
    
    /**
     * Set operations.
     *
     * @param StageInterface[] ops the value to set.
     * @return StageInterface.
     */
    public function setOperations($ops)
    {
        $this->operations = $ops;
        return $this;
    }
    
    /**
     * Add operation.
     *
     * @param OperationInterface operation the value to set.
     * @return Stage.
     */
    public function addOperation(OperationInterface $operation)
    {
		$this->getOperations()->add($operation);
        return $this;
    }
    
    /**
     * Add operations.
     *
     * @param OperationInterface[] operations the value to set.
     * @return Stage.
     */
    public function addOperations($operations)
    {
		foreach ($operations as $operation) {
			$this->addOperation($operation);
		}

        return $this;
    }
    
    /**
     * Add operation.
     *
     * @param OperationInterface operation the value to set.
     * @return Stage.
     */
    public function removeOperation(OperationInterface $operation)
    {
		$this->getOperations()->removeElement($operation);
        return $this;
    }
    
    /**
     * Add operations.
     *
     * @param OperationInterface[] operations the value to set.
     * @return Stage.
     */
    public function removeOperations($operations)
    {
		foreach ($operations as $operation) {
			$this->removeOperation($operation);
		}

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
     * @return StageInterface.
     */
    public function setHints($hints)
    {
        $this->hints = $hints;
        return $this;
    }

	/**
	 * @param HintInterface hint
	 * @return StageInterface
	 */
	public function addHint(HintInterface $hint)
	{
		$hint->setStage($this);
		$this->getHints()->add($hint);
		return $this;
	}

	/**
	 * @param HintInterface hint
	 * @return bool 
	 */
	public function hasHint(HintInterface $hint)
	{
		if ($this->getHints()->contains($hint) !== false) {
			return true;	
		}

		foreach ($this->getChildren() as $child) {
			if ($child->hasHint($hint)) return true;
		}

		return false;
	}

	/**
	 * @return bool
	 */
	public function hasImage()
	{
		return $this->getImages()->count() > 0;
	}

	/**
	 * @return ImageInterface
	 */
	public function getImage()
	{
		return $this->getImages()->first();
	}	
    
    /**
     * Get images.
     *
     * @return Image\ImageInterface[].
     */
    public function getImages()
    {
        return $this->images;
    }
    
    /**
     * Add image.
     *
     * @param Image\IStage image the value to set.
     * @return Stage.
     */
    public function addImage(Image\IStage $image)
    {
		$image->setSource($this);
		$this->getImages()->add($image);
        return $this;
    }
    
    /**
     * Add images.
     *
     * @param Image\IStage[] images the value to set.
     * @return Stage.
     */
    public function addImages($images)
    {
		foreach ($images as $image) {
			$this->addImage($image);
		}

        return $this;
    }
    
    /**
     * Add image.
     *
     * @param Image\IStage image the value to set.
     * @return Stage.
     */
    public function removeImage(Image\IStage $image)
    {
		$image->setSource();
		$this->getImages()->removeElement($image);
        return $this;
    }
    
    /**
     * Add images.
     *
     * @param Image\IStage[] images the value to set.
     * @return Stage.
     */
    public function removeImages($images)
    {
		foreach ($images as $image) {
			$this->removeImage($image);
		}

        return $this;
    }
    
    /**
     * Set images.
     *
     * @param Image\ImageInterface[] images the value to set.
     * @return StageInterface.
     */
    public function setImages($images)
    {
        $this->images = $images;
        return $this;
    }
    
    /**
     * Get comments.
     *
     * @return StageInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return StageInterface.
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
     * Get order.
     *
     * @return int.
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * Set order.
     *
     * @param int order the value to set.
     * @return Stage.
     */
    public function setOrder($order)
    {
        $this->order = (int) $order;
        return $this;
    }

    /**
     * Get actions.
     *
     * @return ActionInterface[]
     */
    public function getActions()
    {
        return $this->actions;
    }
    
    /**
     * Set actions.
     *
     * @param ActionInterface[] actions the value to set.
     * @return Stage.
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
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
     * @return Stage.
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
     * @return Stage.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @ORM\PreUpdate
	 * @return Stage
	 */
	public function preUpdate()
	{
		return $this->setUpdated(new DateTime);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getId();
	}

	/**
	 * @inheritDoc
	 */
	public function __clone()
	{
		$this->id 		    = null;
		$this->commentCount = 0;
		$this->comments		= new ArrayCollection;
		$this->created    	= new DateTime;
		$this->updated    	= new DateTime;

		//Associations
		$images = $this->getImages();
		$this->images = new ArrayCollection;
		foreach ($images as $image) $this->addImage(clone $image);
		$hints = $this->getHints();
		$this->hints = new ArrayCollection;
		foreach ($hints as $hint) $this->addHint(clone $hint);
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
	public function prepareLinks(\ZF\Hal\Link\LinkCollection $links)
	{
		//$self = $links->get('self');
		//$self->setRouteParams(array_merge(
		//	$self->getRouteParams(),
		//	['id' => $this->getProcess()->getId()]
		//));
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
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'image',
				'privilege'   => 'image',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/stage/image',
				    'params'  => ['action' => 'image'],
				],
			],
			[
				'rel'   	  => 'hint',
				'privilege'   => 'hint',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'hint', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'hints',
				'privilege'   => 'hints',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'hints', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
