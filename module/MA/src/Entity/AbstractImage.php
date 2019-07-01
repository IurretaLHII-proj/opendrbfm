<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
    Doctrine\Common\Collections\ArrayCollection;

use DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="image")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "stage"      = "MA\Entity\Image\IStage",
 *     "simulation" = "MA\Entity\Image\ISimulation",
 * })
 */
abstract class AbstractImage extends \Image\Entity\Image
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", name="filename")
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     */
    protected $type;

    /**
     * @ORM\Column(type="integer")
     */
    protected $size;

    /**
     * @ORM\Column(
     *    type="string",
     *    name="descr",
     *    options = {"nullable":true}
     * )
     */
    protected $description;

    /** 
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\User",
     *     inversedBy="images"
     * )
     * @ORM\JoinColumn(
     *     name = "uid",
     *     referencedColumnName = "user_id"
     * )
     */
    protected $user;

    /**
     * @return string
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
        return [];
    }

    /**
     * @inheritDoc
     */
    public function __clone()
    {
        $this->id        = null;
        $this->user    = null;
        $this->created = new DateTime;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return mixed
     */
    abstract public function getSource();
}
