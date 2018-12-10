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
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	ProcessInterface 
{
	const COMPLEXITY_HARD 	= "A";
	const COMPLEXITY_MEDIUM = "BB";
	const COMPLEXITY_SOFT 	= "AA";

	/**
	 * @var int 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var int 
	 * @ORM\Column(type="integer")
	 */
	protected $number;

	/**
	 * @var int 
	 * @ORM\Column(type="integer")
	 */
	protected $code;

	/**
	 * @var int 
	 * @ORM\Column(type="integer")
	 */
	protected $line;

	/**
	 * @var string 
	 * @ORM\Column(type="string")
	 */
	protected $title;

	/**
	 * @var string 
	 * @ORM\Column(type="string")
	 */
	protected $machine;

	/**
	 * @var string 
	 * @ORM\Column(type="string")
	 */
	protected $plant;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="p_name")
	 */
	protected $pieceName;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="p_num")
	 */
	protected $pieceNumber;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="complex", options="{default:'AA'")
	 */
	protected $complexity;

    /**
	 * @var CustomerInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Customer",
     *     inversedBy="proccesses"
     * )
     * @ORM\JoinColumn(
     *     name = "ctm_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $customer;

	/**
	 * @var string 
	 * @ORM\Column(type="string", options="{nullable: true}")
	 */
	protected $body;

	/**
	 * @var User
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "processes",
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
     * Get number.
     *
     * @return int.
     */
    public function getNumber()
    {
        return $this->number;
    }
    
    /**
     * Set number.
     *
     * @param int number the value to set.
     * @return Process.
     */
    public function setNumber($number)
    {
        $this->number = (int) $number;
        return $this;
    }

    /**
     * Get code.
     *
     * @return int.
     */
    public function getCode()
    {
        return $this->code;
    }
    
    /**
     * Set code.
     *
     * @param int code the value to set.
     * @return Process.
     */
    public function setCode($code)
    {
        $this->code = (int) $code;
        return $this;
    }

    /**
     * Get line.
     *
     * @return int.
     */
    public function getLine()
    {
        return $this->line;
    }
    
    /**
     * Set line.
     *
     * @param int line the value to set.
     * @return Process.
     */
    public function setLine($line)
    {
        $this->line = (int) $line;
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
     * Get machine.
     *
     * @return string.
     */
    public function getMachine()
    {
        return $this->machine;
    }
    
    /**
     * Set machine.
     *
     * @param string machine the value to set.
     * @return Process.
     */
    public function setMachine($machine)
    {
        $this->machine = (string) $machine;
        return $this;
    }

    /**
     * Get plant.
     *
     * @return string.
     */
    public function getPlant()
    {
        return $this->plant;
    }
    
    /**
     * Set plant.
     *
     * @param string plant the value to set.
     * @return Process.
     */
    public function setPlant($plant)
    {
        $this->plant = (string) $plant;
        return $this;
    }

    /**
     * Get complexity.
     *
     * @return string.
     */
    public function getComplexity()
    {
        return $this->complexity;
    }
    
    /**
     * Set complexity.
     *
     * @param string complexity the value to set.
     * @return Process.
     */
    public function setComplexity($complexity)
    {
        $this->complexity = (string) $complexity;
        return $this;
    }
    
    /**
     * Get pieceName.
     *
     * @return string.
     */
    public function getPieceName()
    {
        return $this->pieceName;
    }
    
    /**
     * Set pieceName.
     *
     * @param string pieceName the value to set.
     * @return Process.
     */
    public function setPieceName($pieceName)
    {
        $this->pieceName = (string) $pieceName;
        return $this;
    }
    
    /**
     * Get pieceNumber.
     *
     * @return string.
     */
    public function getPieceNumber()
    {
        return $this->pieceNumber;
    }
    
    /**
     * Set pieceNumber.
     *
     * @param string pieceNumber the value to set.
     * @return Process.
     */
    public function setPieceNumber($pieceNumber)
    {
        $this->pieceNumber = (string) $pieceNumber;
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
     * Get customer.
     *
     * @return CustomerInterface.
     */
    public function getCustomer()
    {
        return $this->customer;
    }
    
    /**
     * Set customer.
     *
     * @param CustomerInterface customer the value to set.
     * @return Process.
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * Get stages.
     *
     * @return StageInterface[]
     */
	public function getVersions()
	{
		$levels = new ArrayCollection;

		foreach ($this->getStages() as $stage) {
			if (!$stage->hasParent()) $levels->add($stage);
		}

		return $levels;
	}

	/**
	 * @param StageInterface $stage
	 * @return int|false
	 */
	public function getVersion(StageInterface $stage)
	{
		foreach ($this->getVersions() as $index => $version) {
			if ($version === $stage || $version->hasChild($stage, true)) {
				return $index + 1;
			}
		}

		return false;
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
		return substr($this->getTitle(), 0, 15) . "...";
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
				    'name'    => 'process/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'stage',
				'privilege'   => 'stage',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/detail/json',
				    'params'  => ['action' => 'stage', 'id' => $this->getId()],
				],
			],
		];
	}
}
