<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use DateTime;

/**
 * @ORM\Entity(repositoryClass = "MA\Doctrine\Repository\IssueRepository")
 * @ORM\Table(name="process_stage")
 */
class Stage implements 
	//ResourceInterface, 
	//\User\Entity\UserAwareInterface,
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
	 *	mappedBy	 = "process",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @var Stage\ImageInterface[]
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
     * @return AbstractIssue.
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
     * @return Issue.
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
     * @return Issue.
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
     * @return Issue.
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
		return $this->setUpdated(new DateTime);
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->getTitle();
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
