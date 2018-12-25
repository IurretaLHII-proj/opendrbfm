<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="process_hint_simulation")
 */
class Simulation implements 
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	SimulationInterface 
{
	const STATE_CREATED	      = 0;
	const STATE_NOT_PROCESSED = 0;
	const STATE_IN_PROGRESS   = 1;
	const STATE_FINISHED  	  = 2;
	const STATE_NOT_NECESSARY = -1;
	const STATE_CANCELED  	  = -2;

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
	 *	inversedBy	 = "hints",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "uid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $user;

    /**
	 * @var HintInterface
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Hint",
     *     inversedBy="simulations"
     * )
     * @ORM\JoinColumn(
     *     name = "hint_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $hint;

	/**
	 * @var int
	 * @ORM\Column(type="integer", options={"default":0})
	 */
	protected $state = self::STATE_CREATED;

	/**
	 * @var string 
	 * @ORM\Column(type="string", options={"nullable":true})
	 */
	protected $who;

	/**
	 * @var DateTime 
	 * @ORM\Column(type="datetime", name="whn", options={"nullable":true})
	 */
	protected $when;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="eff", options = {"nullable":true})
	 */
	protected $effect;

	/**
	 * @var string 
	 * @ORM\Column(type="string", name="prev", options = {"nullable":true})
	 */
	protected $prevention;

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
     * @return Simulation.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }
    
    /**
     * Set state.
     *
     * @param int state the value to set.
     * @return Simulation.
     */
    public function setState($state)
    {
        $this->state = (int) $state;
        return $this;
    }
    
    /**
     * Get state.
     *
     * @return int.
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Get who.
     *
     * @return string.
     */
    public function getWho()
    {
        return $this->who;
    }
    
    /**
     * Set who.
     *
     * @param string who the value to set.
     * @return Simulation.
     */
    public function setWho($who = null)
    {
        $this->who = $who ? (string) $who : $who;
        return $this;
    }
    
    /**
     * Get when.
     *
     * @return DateTime.
     */
    public function getWhen()
    {
        return $this->when;
    }
    
    /**
     * Set when.
     *
     * @param DateTime when the value to set.
     * @return Simulation.
     */
    public function setWhen(DateTime $when = null)
    {
        $this->when = $when;
        return $this;
    }
    
    /**
     * Get effect.
     *
     * @return string|null.
     */
    public function getEffect()
    {
        return $this->effect;
    }
    
    /**
     * Set effect.
     *
     * @param strin|nullg effect the value to set.
     * @return Simulation.
     */
    public function setEffect($effect = null)
    {
		$this->effect = $effect ? (string) $effect : $effect;
        return $this;
    }
    
    /**
     * Get prevention.
     *
     * @return string|null.
     */
    public function getPrevention()
    {
        return $this->prevention;
    }
    
    /**
     * Set prevention.
     *
     * @param strin|nullg prevention the value to set.
     * @return Simulation.
     */
    public function setPrevention($prevention = null)
    {
		$this->prevention = $prevention ? (string) $prevention : $prevention;
        return $this;
    }
    
    /**
     * Get hint.
     *
     * @return HintInterface.
     */
    public function getHint()
    {
        return $this->hint;
    }
    
    /**
     * Set hint.
     *
     * @param HintInterface hint the value to set.
     * @return Simulation.
     */
    public function setHint(HintInterface $hint)
    {
        $this->hint = $hint;
        return $this;
    }

	/**
     * Get process.
     *
	 * @return ProcessInterface
	 */
	public function getProcess()
	{
		return $this->getHint()->getProcess();
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
     * @return Simulation.
     */
    public function setUpdated(DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }

	/**
	 * @ORM\PreUpdate
	 * @return Simulation
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
            'id'          => $this->getId(),
            'description' => $this->getDescription(),
            'created'     => $this->getCreated()->getTimestamp(),
        );
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
		];
	}
}
