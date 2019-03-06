<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use DateTime;
use JsonSerializable;

/**
 * @ORM\Entity()
 * @ORM\Table(name="process_version")
 */
class Version implements 
	JsonSerializable,
	ResourceInterface, 
	CommentProviderInterface,
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	//\Base\Hal\LinkPrepareAware,
	VersionInterface 
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
	protected $name;

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
	 *	targetEntity = "MA\Entity\Process",
	 *	inversedBy	 = "versions",
	 * )
	 * @ORM\JoinColumn(
     *  name = "prc_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $process;

	/**
	 * @var Material 
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Material",
	 *	inversedBy	 = "versions",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "mtl_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $material;

	/**
	 * @var User
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "versions",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

	/**
	 * @var StageInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Stage",
	 *	mappedBy	 = "version",
	 *	cascade = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"order" = "ASC"})
	 */
	protected $stages;

	/**
	 * @var CommentInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Comment\Version",
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
		$this->stages   = new ArrayCollection;
		$this->comments = new ArrayCollection;
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
     * @return Version.
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
     * @return Version.
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }
    
    /**
     * Get material.
     *
     * @return MaterialInterface.
     */
    public function getMaterial()
    {
        return $this->material;
    }
    
    /**
     * Set material.
     *
     * @param MaterialInterface material the value to set.
     * @return Version.
     */
    public function setMaterial(MaterialInterface $material)
    {
        $this->material = $material;
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
     * Get stages.
     *
     * @return StageInterface[]
     */
    public function getStages()
    {
        return $this->stages;
    }
    
    /**
     * Set stages.
     *
     * @param StageInterface[] stages the value to set.
     * @return Version.
     */
    public function setStages($stages)
    {
        $this->stages = $stages;
        return $this;
    }

	/**
     * Add stages.
     *
	 * @param StageInterface $stage
     * @return Version.
	 */
	public function addStage(StageInterface $stage)
	{
		$stage->setVersion($this);
		$this->getStages()->add($stage);
		return $this;
	}
    
    /**
     * Add stages.
     *
     * @param StageInterface[] stages the value to set.
     * @return Version.
     */
    public function addStages($stages)
    {
		foreach ($stages as $stage) {
			$this->addStage($stage);
		}

        return $this;
    }
    
    /**
     * Add stage.
     *
     * @param StageInterface stage the value to set.
     * @return Version.
     */
    public function removeStage(StageInterface $stage)
    {
		//FIXME
		//$stage->setProcess();
		//$this->getStages()->removeElement($stage);
        return $this;
    }
    
    /**
     * Add stages.
     *
     * @param StageInterface[] stages the value to set.
     * @return Version.
     */
    public function removeStages($stages)
    {
		foreach ($stages as $stage) {
			$this->removeStage($stage);
		}

        return $this;
    }
    
    /**
     * Get comments.
     *
     * @return VersionInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return VersionInterface.
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
     * @return Version.
     */
    public function setDescription($descr = null)
    {
        $this->description = $descr ? (string) $descr : $descr;
        return $this;
    }

	/**
	 * @return ImageInterface|null
	 */
	public function getImage()
	{	
		for ($i = $this->getStages()->count()-1; $i >= 0; $i--) {
			$images = $this->getStages()->get($i)->getImages();
			if (!$images->isEmpty()) {
				return $images->first();
			}
		}
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
     * @return Version.
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
     * @return Version.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
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
	public function __clone()
	{
		$this->id 	    	= null;
		$this->commentCount = 0;
		$this->created      = new DateTime;
		$this->updated      = new DateTime;
		$this->stages       = new ArrayCollection;
		$this->comments     = new ArrayCollection;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		return [
			'id' 		   => $this->getId(),
			'name' 		   => $this->getName(),
			'owner'		   => $this->getUser(),
			'material'     => $this->getMaterial(),
			'description'  => $this->getDescription(),
			'commentCount' => $this->getCommentCount(),
			//'stages'  	  => new \ZF\Hal\Collection($this->getStages()),
			'updated'	   => $this->getUpdated(),
			'created'	   => $this->getCreated(),
		];
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		$links = [
			[
				'rel'   	  => 'edit',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/version/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/version/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'stage',
				'resource'	  => $this,
				'privilege'   => 'stage',
				'route' => [
				    'name'    => 'process/version/detail/json',
				    'params'  => ['action' => 'stage', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'clone',
				'resource'	  => $this,
				'privilege'   => 'clone',
				'route' => [
				    'name'    => 'process/version/detail/json',
				    'params'  => ['action' => 'clone', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'stages',
				'resource'	  => $this,
				'privilege'   => 'stages',
				'route' => [
				    'name'    => 'process/version/detail/json',
				    'params'  => ['action' => 'stages', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'resource'	  => $this,
				'privilege'   => 'comment',
				'route' => [
				    'name'    => 'process/version/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'resource'	  => $this,
				'privilege'   => 'comments',
				'route' => [
				    'name'    => 'process/version/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];

		return $links;
	}
}
