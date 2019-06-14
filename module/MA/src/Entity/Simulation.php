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
     *     targetEntity="MA\Entity\HintInfluence",
     *     inversedBy="simulations"
     * )
     * @ORM\JoinColumn(
     *     name = "src_id",
	 *     referencedColumnName = "id"
     * )
     */
    protected $influence;

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
	 * @var Note\HintEffect[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\HintEffect",
	 *	mappedBy	 = "simulation",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $effects;

	/**
	 * @var Note\HintPrevention[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Note\HintPrevention",
	 *	mappedBy	 = "simulation",
	 *	cascade 	 = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $preventions;

	/**
	 * @var int
	 * @ORM\Column(type="integer", options={"default":-1})
	 */
	protected $state = self::STATE_NOT_NECESSARY;

	/**
	 * @var User
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "simulations",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "who",
	 *	referencedColumnName = "user_id"
	 * )
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
	 * @var Image\ISimulation[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Image\ISimulation",
	 *	mappedBy	 = "source",
	 *	cascade = {"persist", "remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $images;

	/**
	 * @var ActionInterface[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Action\Simulation",
	 *	mappedBy	 = "source",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $actions;

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
		$this->effects     = new ArrayCollection;
		$this->suggestions = new ArrayCollection;
		$this->preventions = new ArrayCollection;
		$this->images      = new ArrayCollection;
		$this->actions     = new ArrayCollection;
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
     * @return UserInterface|null.
     */
    public function getWho()
    {
        return $this->who;
    }
    
    /**
     * Set who.
     *
     * @param UserInterface|null who the value to set.
     * @return Simulation.
     */
    public function setWho(\MA\Entity\UserInterface $who = null)
    {
        $this->who = $who;
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
        return $this->getInfluence()->getHint();
    }
    
    /**
     * Get influence.
     *
     * @return HintInfluenceInterface.
     */
    public function getInfluence()
    {
        return $this->influence;
    }
    
    /**
     * Set influence.
     *
     * @param HintInfluenceInterface influence the value to set.
     * @return SimulationInterface.
     */
    public function setInfluence(HintInfluenceInterface $influence)
    {
        $this->influence = $influence;
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
     * @param Image\ISimulation image the value to set.
     * @return Simulation.
     */
    public function addImage(Image\ISimulation $image)
    {
		$image->setSource($this);
		$this->getImages()->add($image);
        return $this;
    }
    
    /**
     * Add images.
     *
     * @param Image\ISimulation[] images the value to set.
     * @return Simulation.
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
     * @param Image\ISimulation image the value to set.
     * @return Simulation.
     */
    public function removeImage(Image\ISimulation $image)
    {
		$image->setSource();
		$this->getImages()->removeElement($image);
        return $this;
    }
    
    /**
     * Add images.
     *
     * @param Image\ISimulation[] images the value to set.
     * @return Simulation.
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
     * @return SimulationInterface.
     */
    public function setImages($images)
    {
        $this->images = $images;
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
     * Get stage.
     *
     * @return StageInterface.
	 */
	public function getStage()
	{
		return $this->getHint()->getStage();
	}

	/**
     * Get version.
     *
     * @return VersionInterface.
	 */
	public function getVersion()
	{
		return $this->getHint()->getVersion();
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
     * @return SimulationInterface.
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
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
     * Get effects.
     *
     * @return Note\HintEffect[].
     */
    public function getEffects()
    {
        return $this->effects;
    }
    
    /**
     * Set effects.
     *
     * @param Note\HintEffect[] effects the value to set.
     * @return SimulationInterface.
     */
    public function setEffects($effects)
    {
        $this->effects = $effects;
        return $this;
    }
    
    /**
     * Add effect.
     *
     * @param Note\HintEffect effect the value to set.
     * @return SimulationInterface.
     */
    public function addEffect(Note\HintEffect $effect)
    {
		$effect->setSimulation($this);
		$this->getEffects()->add($effect);
        return $this;
    }
    
    /**
     * Add effects.
     *
     * @param Note\HintEffect[] effects the value to set.
     * @return SimulationInterface.
     */
    public function addEffects($effects)
    {
		foreach ($effects as $effect) {
			$this->addEffect($effect);
		}

        return $this;
    }
    
    /**
     * Add effect.
     *
     * @param Note\HintEffect effect the value to set.
     * @return SimulationInterface.
     */
    public function removeEffect(Note\HintEffect $effect)
    {
		$effect->setSimulation();
		$this->getEffects()->removeElement($effect);
        return $this;
    }
    
    /**
     * Add effects.
     *
     * @param Note\HintEffect[] effects the value to set.
     * @return SimulationInterface.
     */
    public function removeEffects($effects)
    {
		foreach ($effects as $effect) {
			$this->removeEffect($effect);
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
     * Get preventions.
     *
     * @return Note\HintPrevention[].
     */
    public function getPreventions()
    {
        return $this->preventions;
    }
    
    /**
     * Set preventions.
     *
     * @param Note\HintPrevention[] preventions the value to set.
     * @return Hint.
     */
    public function setPreventions($preventions)
    {
        $this->preventions = $preventions;
        return $this;
    }
    
    /**
     * Add prevention.
     *
     * @param Note\HintPrevention prevention the value to set.
     * @return Stage.
     */
    public function addPrevention(Note\HintPrevention $prevention)
    {
		$prevention->setSimulation($this);
		$this->getPreventions()->add($prevention);
        return $this;
    }
    
    /**
     * Add preventions.
     *
     * @param Note\HintPrevention[] preventions the value to set.
     * @return Stage.
     */
    public function addPreventions($preventions)
    {
		foreach ($preventions as $prevention) {
			$this->addPrevention($prevention);
		}

        return $this;
    }
    
    /**
     * Add prevention.
     *
     * @param Note\HintPrevention prevention the value to set.
     * @return Stage.
     */
    public function removePrevention(Note\HintPrevention $prevention)
    {
		$prevention->setSimulation();
		$this->getPreventions()->removeElement($prevention);
        return $this;
    }
    
    /**
     * Add preventions.
     *
     * @param Note\HintPrevention[] preventions the value to set.
     * @return Stage.
     */
    public function removePreventions($preventions)
    {
		foreach ($preventions as $prevention) {
			$this->removePrevention($prevention);
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
			'images'  	   => new \ZF\Hal\Collection($this->getImages()),
			'effects' 	   => new \ZF\Hal\Collection($this->getEffects()),
			'suggestions'  => new \ZF\Hal\Collection($this->getSuggestions()),
			'preventions'  => new \ZF\Hal\Collection($this->getPreventions()),
        );
    }

	/**
	 * @inheritDoc
	 */
	public function __clone()
	{
		$this->id 		    = null;
		$this->commentCount = 0;
		$this->comments     = new ArrayCollection;
		$this->created      = new DateTime;
		$this->updated      = new DateTime;

		//Associations
		$images = $this->getImages();
		$this->images = new ArrayCollection;
		foreach ($images as $image) $this->addImage(clone $image);
		$effects = $this->getEffects();
		$this->effects      = new ArrayCollection;
		foreach ($effects as $effect) $this->addEffect(clone $effect);
		$preventions = $this->getPreventions();
		$this->preventions      = new ArrayCollection;
		foreach ($preventions as $prevention) $this->addPrevention(clone $prevention);
		$suggestions = $this->getSuggestions();
		$this->suggestions      = new ArrayCollection;
		foreach ($suggestions as $suggestion) $this->addSuggestion(clone $suggestion);
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
				'rel'   	  => 'image',
				'privilege'   => 'image',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/image',
				    'params'  => ['action' => 'image'],
				],
			],
			[
				'rel'   	  => 'effect',
				'privilege'   => 'effect',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'effect', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'prevention',
				'privilege'   => 'prevention',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/hint/simulation/detail/json',
				    'params'  => ['action' => 'prevention', 'id' => $this->getId()],
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
