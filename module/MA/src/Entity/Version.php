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
    const STATE_IN_PROGRESS   = 0;
    const STATE_APROVED       = 1;
    const STATE_CANCELED      = -1;

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
     * @var int
     * @ORM\Column(type="integer", options={"default":-0})
     */
    protected $state = self::STATE_IN_PROGRESS;

    /**
     * @var User
     * @ORM\ManyToOne(
     *    targetEntity = "MA\Entity\Process",
     *    inversedBy     = "versions",
     * )
     * @ORM\JoinColumn(
     *  name = "prc_id",
     *    referencedColumnName = "id"
     * )
     */
    protected $process;

    /**
     * @var Version 
     * @ORM\ManyToOne(
     *    targetEntity = "MA\Entity\Version",
     *    inversedBy     = "children",
     * )
     * @ORM\JoinColumn(
     *    name= "prt_id",
     *    referencedColumnName = "id",
     *    nullable = true
     * )
     */
    protected $parent;

    /**
     * @var Version[]
     * @ORM\OneToMany(
     *    targetEntity = "MA\Entity\Version",
     *    mappedBy     = "parent",
     *    cascade      = {"persist", "remove"}
     * )
     * @ORM\OrderBy({"created" = "ASC"})
     */
    protected $children;

    /**
     * @var VersionType 
     * @ORM\ManyToOne(
     *    targetEntity = "MA\Entity\VersionType",
     *    inversedBy     = "versions",
     * )
     * @ORM\JoinColumn(
     *    name= "type_id",
     *    referencedColumnName = "id"
     * )
     */
    protected $type;

    /**
     * @var Material 
     * @ORM\ManyToOne(
     *    targetEntity = "MA\Entity\Material",
     *    inversedBy     = "versions",
     * )
     * @ORM\JoinColumn(
     *    name= "mtl_id",
     *    referencedColumnName = "id"
     * )
     */
    protected $material;

    /**
     * @var User
     * @ORM\ManyToOne(
     *    targetEntity = "MA\Entity\User",
     *    inversedBy     = "versions",
     * )
     * @ORM\JoinColumn(
     *    name= "uid",
     *    referencedColumnName = "user_id"
     * )
     */
    protected $user;

    /**
     * @var StageInterface[]
     * @ORM\OneToMany(
     *    targetEntity = "MA\Entity\Stage",
     *    mappedBy     = "version",
     *    cascade = {"persist", "remove"}
     * )
     * @ORM\OrderBy({"order" = "ASC"})
     */
    protected $stages;

    /**
     * @var ActionInterface[]
     * @ORM\OneToMany(
     *    targetEntity = "MA\Entity\Action\Version",
     *    mappedBy     = "source",
     *    cascade      = {"remove"}
     * )
     * @ORM\OrderBy({"created" = "ASC"})
     */
    protected $actions;

    /**
     * @var CommentInterface[]
     * @ORM\OneToMany(
     *    targetEntity = "MA\Entity\Comment\Version",
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
        $this->actions  = new ArrayCollection;
        $this->children = new ArrayCollection;
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
     * Get type.
     *
     * @return VersionTypeInterface.
     */
    public function getType()
    {
        return $this->type;
    }
    
    /**
     * Set type.
     *
     * @param VersionTypeInterface type the value to set.
     * @return Version.
     */
    public function setType(VersionTypeInterface $type)
    {
        $this->type = $type;
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
     * @return Version|null.
     */
    public function getParent()
    {
        return $this->parent;
    }
    
    /**
     * Set parent.
     *
     * @param Version parent the value to set.
     * @return Version.
     */
    public function setParent(Version $parent = null)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isParent()
    {
        return $this->getParent() === null;
    }
    
    /**
     * Get children.
     *
     * @return Version[].
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * Set children.
     *
     * @param Version[] children the value to set.
     * @return Version.
     */
    public function setChildren($children)
    {
        $this->children = $children;
        return $this;
    }

    /**
     * @return boolean
     */
    public function hasChildren()
    {
        return $this->getChildren()->count() > 0;
    }

    /**
     * @param VersionInterface $version
     * @return Version
     */
    public function addChild(VersionInterface $version)
    {
        $version->setParent($this);
        $this->getChildren()->add($version);
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
     * @return Version.
     */
    public function setProcess(ProcessInterface $process)
    {
        $this->process = $process;
        return $this;
    }
    
    /**
     * Set state.
     *
     * @param int state the value to set.
     * @return Version.
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
     * Get stage.
     * 
     * @param int $order
     * @return StageInterface
     */
    public function getStage($order)
    {
        foreach ($this->getStages() as $stage) {
            if ($stage->getOrder() == (int) $order) return $stage;
        }
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
        $this->getStages()->removeElement($stage);
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
     * @return Version.
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
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
        $this->id           = null;
        $this->name         = "Clone of " . $this->name;
        $this->user         = null;
        $this->parent       = null;
        $this->created      = new DateTime;
        $this->updated      = new DateTime;
        $this->children     = new ArrayCollection;
        $this->comments     = new ArrayCollection;
        $this->commentCount = 0;

        //Associations
        $stages = $this->getStages();
        $this->stages = new ArrayCollection;
        foreach ($stages as $stage) $this->addStage(clone $stage);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'id'            => $this->getId(),
            'name'            => $this->getName(),
            'owner'           => $this->getUser(),
            'parent'       => $this->getParent(),
            'type'           => $this->getType(),
            'state'           => $this->getState(),
            'material'     => $this->getMaterial(),
            'description'  => $this->getDescription(),
            'commentCount' => $this->getCommentCount(),
            //'stages'        => new \ZF\Hal\Collection($this->getStages()),
            'updated'       => $this->getUpdated(),
            'created'       => $this->getCreated(),
        ];
    }

    /**
       * @inheritDoc
       */
      public function provideLinks()
      {
        $links = [
            [
                'rel'         => 'edit',
                'privilege'   => 'edit',
                'resource'      => $this,
                'route' => [
                    'name'    => 'process/version/detail/json',
                    'params'  => ['action' => 'edit', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'delete',
                'privilege'   => $this->getChildren()->count() ? false : 'delete',
                'resource'      => $this,
                'route' => [
                    'name'    => 'process/version/detail/json',
                    'params'  => ['action' => 'delete', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'stage',
                'resource'      => $this,
                'privilege'   => 'stage',
                'route' => [
                    'name'    => 'process/version/detail/json',
                    'params'  => ['action' => 'stage', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'clone',
                'resource'      => $this,
                'privilege'   => 'clone',
                'route' => [
                    'name'    => 'process/version/detail/json',
                    'params'  => ['action' => 'clone', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'stages',
                'resource'      => $this,
                'privilege'   => 'stages',
                'route' => [
                    'name'    => 'process/version/detail/json',
                    'params'  => ['action' => 'stages', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'pdf',
                'privilege'   => 'pdf',
                'resource'      => $this,
                'route' => [
                    'name'    => 'process/version/detail',
                    'params'  => ['action' => 'pdf', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'excel',
                'privilege'   => 'excel',
                'resource'      => $this,
                'route' => [
                    'name'    => 'process/version/detail',
                    'params'  => ['action' => 'excel', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'comment',
                'resource'      => $this,
                'privilege'   => 'comment',
                'route' => [
                    'name'    => 'process/version/detail/json',
                    'params'  => ['action' => 'comment', 'id' => $this->getId()],
                ],
            ],
            [
                'rel'         => 'comments',
                'resource'      => $this,
                'privilege'   => 'comments',
                'route' => [
                    'name'    => 'process/version/detail/json',
                    'params'  => ['action' => 'comments', 'id' => $this->getId()],
                ],
            ],
        ];

        if (null !== ($image = $this->getImage())) {
            $links[] = [
                'rel'         => 'image',
                'privilege'   => 'image',
                'resource'      => $this,
                'route' => [
                    'name'    => 'image/detail',
                    'params'  => ['action' => 'detail', 'id' => $image->getId()],
                ],
            ];
        }

        return $links;
    }
}
