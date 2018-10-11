<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use DateTime;

/**
 * @ORM\Entity(repositoryClass = "MA\Doctrine\Repository\ProcessRepository")
 * @ORM\Table(name="process")
 */
class Process implements 
	//ResourceInterface, 
	//\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	ProcessInterface 
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
	protected $title;

	/**
	 * @var string 
	 * @ORM\Column(type="string", options="{nullable: true}")
	 */
	protected $body;

	/**
	 * @var StageInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Stage",
	 *	mappedBy	 = "process",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $stages;

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
     * @return Process.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Get title.
     *
     * @return string.
     */
    public function getTitle()
    {
        return $this->title;
    }
    
    /**
     * Set title.
     *
     * @param string title the value to set.
     * @return Process.
     */
    public function setTitle($title)
    {
        $this->title = (string) $title;
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
     * @return Process.
     */
    public function setBody($body = null)
    {
        $this->body = $body ? (string) $body : $body;
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
     * Get primary stage.
     *
	 * @param int $index
     * @return StageInterface
     */
	public function getStage($index = 0)
	{
		return $this->getStages()->get($index);
	}

	/**
     * Add stages.
     *
	 * @param StageInterface $stage
     * @return Process.
	 */
	public function addStage(StageInterface $stage)
	{
		$stage->setProcess($this);
		$this->getStages()->add($stage);
		return $this;
	}
    
    /**
     * Set stages.
     *
     * @param StageInterface[] stages the value to set.
     * @return Process.
     */
    public function setStages($stages)
    {
        $this->stages = $stages;
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
     * @return Process.
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
     * @return Process.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @ORM\PreUpdate
	 * @return Process
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
				    'name'    => 'process/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'stage',
				//'privilege'   => 'stage',
				'route' => [
				    'name'    => 'process/detail/json',
				    'params'  => ['action' => 'stage', 'id' => $this->getId()],
				],
			],
		];
	}
}
