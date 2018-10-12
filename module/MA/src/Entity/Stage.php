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
	//ResourceInterface, 
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
		$this->created  = new DateTime;
		$this->updated  = new DateTime;
		$this->children = new ArrayCollection;
		$this->images   = new ArrayCollection;
		$this->hints	= new ArrayCollection;
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
    public function getChildren()
    {
        return $this->children;
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
		return static::class;
	}

	/**
	 * @inheritDoc
	 */
	public function prepareLinks(\ZF\Hal\Link\LinkCollection $links)
	{
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'edit',
				//'privilege'   => 'edit',
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'hint',
				//'privilege'   => 'hint',
				'route' => [
				    'name'    => 'process/stage/detail/json',
				    'params'  => ['action' => 'hint', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'image',
				//'privilege'   => 'image',
				'route' => [
				    'name'    => 'process/stage/image',
				    'params'  => ['action' => 'image'],
				],
			],
		];
	}
}
