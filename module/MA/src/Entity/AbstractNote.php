<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="note")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "hint-reason"         = "MA\Entity\Note\HintReason",
 *     "hint-influence"      = "MA\Entity\Note\HintInfluence",
 *     "hint-suggestion"     = "MA\Entity\Note\HintSuggestion",
 *     "hint-effect"         = "MA\Entity\Note\HintEffect",
 *     "hint-prevention"     = "MA\Entity\Note\HintPrevention",
 * })
 */
abstract class AbstractNote implements 
    JsonSerializable, 
    ResourceInterface,
    \User\Entity\UserAwareInterface,
       \Base\Hal\LinkProvider,
    CommentProviderInterface,
    NoteInterface
{
    /**
     * @var int 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var UserInterface
     * @ORM\ManyToOne(
     *    targetEntity = "MA\Entity\User",
     *    inversedBy     = "notes",
     * )
     * @ORM\JoinColumn(
     *    name= "uid",
     *    referencedColumnName = "user_id"
     * )
     */
    protected $user;

    /**
     * @var string 
     * @ORM\Column(type="string")
     */
    protected $text;

    /**
     * @var ActionInterface[]
     * @ORM\OneToMany(
     *    targetEntity = "MA\Entity\Action\Note",
     *    mappedBy     = "source",
     *    cascade = {"remove"}
     * )
     * @ORM\OrderBy({"created" = "ASC"})
     */
    protected $actions;

    /**
     * @var CommentInterface[]
     * @ORM\OneToMany(
     *    targetEntity = "MA\Entity\Comment\Note",
     *    mappedBy     = "source",
     *    cascade      = {"persist", "remove"}
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
     * @return
     */
    public function __construct()
    {
        $this->comments = new ArrayCollection;
        $this->actions  = new ArrayCollection;
        $this->created  = new DateTime;
    }

    /**
     * @inheritDoc
     */
    public function __clone()
    {
        $this->id           = null;
        $this->user         = null;
        $this->commentCount = 0;
        $this->created      = new DateTime;
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
     * @return AbstractNote.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }

    /**
     * Get text.
     *
     * @return string.
     */
    public function getText()
    {
        return $this->text;
    }
    
    /**
     * Set text.
     *
     * @param string text the value to set.
     * @return AbstractNote.
     */
    public function setText($text)
    {
        $this->text = (string) $text;
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
     * @return HintReasonInterface.
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
        return $this;
    }
    
    /**
     * Get comments.
     *
     * @return HintInterface.
     */
    public function getComments()
    {
        return $this->comments;
    }
    
    /**
     * Set comments.
     *
     * @param CommentInterface[] comments the value to set.
     * @return HintInterface.
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
     * @return AbstractNote.
     */
    public function setCreated(DateTime $created)
    {
        $this->created = $created;
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
        return [
            'id'           => $this->getId(),
            'owner'              => $this->getUser(),
            'class'           => static::class,
            'text'           => $this->getText(),
            'commentCount' => $this->getCommentCount(),
            'created'       => $this->getCreated(),
        ];
    
    }

    /**
     * @inheritDoc
     */
    public function getProcess() 
    {
        return $this->getSource()->getProcess();
    }

    /**
     * @inheritDoc
     */
    public function getVersion()
    {
        return $this->getSource()->getVersion();
    }

    /**
     * @inheritDoc
     */
    public function getStage()
    {
        return $this->getSource()->getStage();
    }

    /**
     * @inheritDoc
     */
    public function getHint()
    {
        return $this->getSource()->getHint();
    }

    /**
     * @return string
     */
    public function totring()
    {
        return $this->getText();
    }

    /**
     * @return mixed
     */
    abstract public function getSource();
}
