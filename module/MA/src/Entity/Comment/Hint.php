<?php

namespace MA\Entity\Comment;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use MA\Entity\HintInterface,
	MA\Entity\AbstractComment;

/**
 * @ORM\Entity()
 */
class Hint extends AbstractComment
{
	/**
	 * @var PostComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Comment\Hint",
	 *	inversedBy	 = "children",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "prt_id",
	 *	referencedColumnName = "id",
	 *	nullable = true
	 * )
	 */
	protected $parent;

	/**
	 * @var PostComment[]
	 * @ORM\OneToMany(
	 *	targetEntity = "MA\Entity\Comment\Hint",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Hint",
	 *	inversedBy	 = "comments",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $source;

    /**
     * Get source.
     *
     * @return HintInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param HintInterface source the value to set.
     * @return Hint.
     */
    public function setSource(HintInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get hint.
     *
     * @return Hint.
     */
    public function getHint()
    {
        return $this->getSource();
    }
    
    /**
     * Set hint.
     *
     * @param Hint hint the value to set.
     * @return Hint.
     */
    public function setHint(HintInterface $hint)
    {
        $this->setSource($hint);
        return $this;
    }
}
