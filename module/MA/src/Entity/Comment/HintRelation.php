<?php

namespace MA\Entity\Comment;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use MA\Entity\HintRelationInterface,
	MA\Entity\AbstractComment;

/**
 * @ORM\Entity()
 */
class HintRelation extends AbstractComment
{
	/**
	 * @var PostComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Comment\HintRelation",
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
	 *	targetEntity = "MA\Entity\Comment\HintRelation",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintRelation",
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
     * @return HintRelationInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param HintRelationInterface source the value to set.
     * @return HintRelation.
     */
    public function setSource(HintRelationInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get hint.
     *
     * @return HintRelation.
     */
    public function getRelation()
    {
        return $this->getSource();
    }
    
    /**
     * Set hint.
     *
     * @param HintRelation hint the value to set.
     * @return HintRelation.
     */
    public function setRelation(HintRelationInterface $hint)
    {
        $this->setSource($hint);
        return $this;
    }
}
