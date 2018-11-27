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
	 * @var StageInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Stage",
     *     inversedBy="children"
     * )
     * @ORM\JoinColumn(
     *     name = "stg_id",
	 *     referencedColumnName = "id",
	 *     nullable = true
     * )
     */
    protected $parent;

	/**
	 * @var StageInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Stage",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

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
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"priority" = "DESC"})
	 */
	protected $hints;

	/**
	 * @var Image\IStage[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Image\IStage",
	 *	mappedBy	 = "source",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $images;

    /**
	 * @var ProcessInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Process",
     *     inversedBy="stages"
     * )
     * @ORM\JoinColumn(
     *     name = "prc_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $process;

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
		$this->children   = new ArrayCollection;
		$this->images     = new ArrayCollection;
		$this->hints	  = new ArrayCollection;
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
        return $this->process;
    }
    
    /**
     * Set process.
     *
     * @param ProcessInterface process the value to set.
     * @return Stage.
     */
    public function setProcess(ProcessInterface $process)
    {
        $this->process = $process;
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
	 * @return bool
	 */
	public function hasParent()
	{
		return $this->getParent() !== null;
	}
    
    /**
     * Get parent.
     *
     * @return StageInterface|null.
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Set parent.
     *
     * @param StageInterface|null parent the value to set.
     * @return StageInterface.
     */
    public function setParent(StageInterface $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }
    
    /**
     * Get children.
     *
     * @return StageInterface[].
     */
    public function getChildren($recursive = false)
    {
		$children = $this->children; 
		if (!$recursive) {
			return $children;
		}

		$allChildren = $children->toArray();
		foreach ($this->getChildren() as $child) {
			$allChildren = array_merge($allChildren, $child->getChildren($recursive)->toArray());
		}

		return new ArrayCollection($allChildren);
    }
    
    /**
     * Set children.
     *
     * @param StageInterface[] children the value to set.
     * @return StageInterface.
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

	/**
	 * @param StageInterface $stage
	 * @param boolean $recursive
	 * @return boolean
	 */
	public function hasChild(StageInterface $stage, $recursive = true)
	{
		return $this->getChildren(true)->contains($stage);
	}
    
    /**
     * Add child.
     *
     * @param StageInterface child the value to set.
     * @return Stage.
     */
    public function addChild(StageInterface $child)
    {
		$child->setParent($this);
		$this->getChildren()->add($child);
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
	 * @return int
	 */
	public function getLevel()
	{
		$level = 1;
		$stage = $this;
		while (null !== ($parent = $stage->getParent())) {
			$stage = $parent;
			$level++; 
		}
		return $level;
	}

	/**
	 * @return int|false
	 */
	public function getVersion()
	{
		return $this->getProcess()->getVersion($this);
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
				'rel'   	  => 'process',
				'privilege'   => 'detail',
				'resource'	  => $this->getProcess(),
				'route' => [
				    'name'    => 'process/detail',
				    'params'  => ['action' => 'detail', 'id' => $this->getProcess()->getId()],
					'options' => ['query' => ['stage' => $this->getId()]],
				],
			],
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
				'rel'   	  => 'hint',
				'privilege'   => 'hint',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'hint', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'children',
				'resource'	  => $this,
				'privilege'   => 'detail',
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'children', 'id' => $this->getId()],
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
		];
	}
}
