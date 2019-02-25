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
	JsonSerializable,
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider,
	\Base\Hal\LinkPrepareAware,
	CommentProviderInterface,
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
	 * @var Note\HintReason[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\HintReason",
	 *	mappedBy	 = "simulation",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $reasons;

	/**
	 * @var Note\HintSuggestion[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\HintSuggestion",
	 *	mappedBy	 = "simulation",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $suggestions;

	/**
	 * @var Note\HintInfluence[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\HintInfluence",
	 *	mappedBy	 = "simulation",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $influences;

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
	 * @var CommentInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Comment\Simulation",
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
		$this->reasons     = new ArrayCollection;
		$this->suggestions = new ArrayCollection;
		$this->influences  = new ArrayCollection;
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
        $this->who = $who != null ? (string) $who : $who;
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
     * @return SimulationInterface.
     */
    public function setHint(HintInterface $hint)
    {
        $this->hint = $hint;
        return $this;
    }

	/**
     * Get process.
     *
     * @return SimulationInterface.
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
     * Get comments.
     *
     * @return SimulationInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return SimulationInterface.
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
     * Get reasons.
     *
     * @return Note\HintReason[].
     */
    public function getReasons()
    {
        return $this->reasons;
    }
    
    /**
     * Set reasons.
     *
     * @param Note\HintReason[] reasons the value to set.
     * @return SimulationInterface.
     */
    public function setReasons($reasons)
    {
        $this->reasons = $reasons;
        return $this;
    }
    
    /**
     * Add reason.
     *
     * @param Note\HintReason reason the value to set.
     * @return SimulationInterface.
     */
    public function addReason(Note\HintReason $reason)
    {
		$reason->setSimulation($this);
		$this->getReasons()->add($reason);
        return $this;
    }
    
    /**
     * Add reasons.
     *
     * @param Note\HintReason[] reasons the value to set.
     * @return SimulationInterface.
     */
    public function addReasons($reasons)
    {
		foreach ($reasons as $reason) {
			$this->addReason($reason);
		}

        return $this;
    }
    
    /**
     * Add reason.
     *
     * @param Note\HintReason reason the value to set.
     * @return SimulationInterface.
     */
    public function removeReason(Note\HintReason $reason)
    {
		$reason->setSimulation();
		$this->getReasons()->removeElement($reason);
        return $this;
    }
    
    /**
     * Add reasons.
     *
     * @param Note\HintReason[] reasons the value to set.
     * @return SimulationInterface.
     */
    public function removeReasons($reasons)
    {
		foreach ($reasons as $reason) {
			$this->removeReason($reason);
		}

        return $this;
    }
    
    /**
     * Get suggestions.
     *
     * @return Note\HintSuggestion[].
     */
    public function getSuggestions()
    {
        return $this->suggestions;
    }
    
    /**
     * Set suggestions.
     *
     * @param Note\HintSuggestion[] suggestions the value to set.
     * @return SimulationInterface.
     */
    public function setSuggestions($suggestions)
    {
        $this->suggestions = $suggestions;
        return $this;
    }
    
    /**
     * Add suggestion.
     *
     * @param Note\HintSuggestion suggestion the value to set.
     * @return SimulationInterface.
     */
    public function addSuggestion(Note\HintSuggestion $suggestion)
    {
		$suggestion->setSimulation($this);
		$this->getSuggestions()->add($suggestion);
        return $this;
    }
    
    /**
     * Add suggestions.
     *
     * @param Note\HintSuggestion[] suggestions the value to set.
     * @return SimulationInterface.
     */
    public function addSuggestions($suggestions)
    {
		foreach ($suggestions as $suggestion) {
			$this->addSuggestion($suggestion);
		}

        return $this;
    }
    
    /**
     * Add suggestion.
     *
     * @param Note\HintSuggestion suggestion the value to set.
     * @return SimulationInterface.
     */
    public function removeSuggestion(Note\HintSuggestion $suggestion)
    {
		$suggestion->setSimulation();
		$this->getSuggestions()->removeElement($suggestion);
        return $this;
    }
    
    /**
     * Add suggestions.
     *
     * @param Note\HintSuggestion[] suggestions the value to set.
     * @return SimulationInterface.
     */
    public function removeSuggestions($suggestions)
    {
		foreach ($suggestions as $suggestion) {
			$this->removeSuggestion($suggestion);
		}

        return $this;
    }
    
    /**
     * Get influences.
     *
     * @return Note\HintInfluence[].
     */
    public function getInfluences()
    {
        return $this->influences;
    }
    
    /**
     * Set influences.
     *
     * @param Note\HintInfluence[] influences the value to set.
     * @return Hint.
     */
    public function setInfluences($influences)
    {
        $this->influences = $influences;
        return $this;
    }
    
    /**
     * Add influence.
     *
     * @param Note\HintInfluence influence the value to set.
     * @return Stage.
     */
    public function addInfluence(Note\HintInfluence $influence)
    {
		$influence->setSimulation($this);
		$this->getInfluences()->add($influence);
        return $this;
    }
    
    /**
     * Add influences.
     *
     * @param Note\HintInfluence[] influences the value to set.
     * @return Stage.
     */
    public function addInfluences($influences)
    {
		foreach ($influences as $influence) {
			$this->addInfluence($influence);
		}

        return $this;
    }
    
    /**
     * Add influence.
     *
     * @param Note\HintInfluence influence the value to set.
     * @return Stage.
     */
    public function removeInfluence(Note\HintInfluence $influence)
    {
		$influence->setSimulation();
		$this->getInfluences()->removeElement($influence);
        return $this;
    }
    
    /**
     * Add influences.
     *
     * @param Note\HintInfluence[] influences the value to set.
     * @return Stage.
     */
    public function removeInfluences($influences)
    {
		foreach ($influences as $influence) {
			$this->removeInfluence($influence);
		}

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
            'id'           => $this->getId(),
			'owner'   	   => $this->getUser(),
            'description'  => $this->getDescription(),
            'created'      => $this->getCreated(),
			'owner'   	   => $this->getUser(),
			'state' 	   => $this->getState(),
			'who' 	  	   => $this->getWho(),
			'when' 	  	   => $this->getWhen(),
			'effect'	   => $this->getEffect(),
			'prevention'   => $this->getPrevention(),
			'commentCount' => $this->getCommentCount(),
			'reasons' 	   => new \ZF\Hal\Collection($this->getReasons()),
			'suggestions'  => new \ZF\Hal\Collection($this->getSuggestions()),
			'influences'   => new \ZF\Hal\Collection($this->getInfluences()),
        );
    }

	/**
	 * @inheritDoc
	 */
	public function __clone()
	{
		$this->id 		   = null;
		$this->created     = new DateTime;
		$this->updated     = new DateTime;
		$this->reasons     = new ArrayCollection;
		$this->suggestions = new ArrayCollection;
		$this->influences  = new ArrayCollection;
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
				'rel'   	  => 'render',
				'privilege'   => 'render',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'render', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'edit',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'reason',
				'privilege'   => 'reason',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'reason', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'influence',
				'privilege'   => 'influence',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'influence', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'suggestion',
				'privilege'   => 'suggestion',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'suggestion', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
