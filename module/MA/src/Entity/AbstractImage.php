<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\MappedSuperclass()
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
	 * FIXME
     * @ORM\ManyToOne(
     *     targetEntity="Issues\Entity\User",
     *     inversedBy="images"
     * )
     * @ORM\JoinColumn(
     *     name = "uid",
     *     referencedColumnName = "user_id"
     * )
     */
	//protected $user;

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
	 * @return mixed
	 */
	abstract public function getSource();
}
