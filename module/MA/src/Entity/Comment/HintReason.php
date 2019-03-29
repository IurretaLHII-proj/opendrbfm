<?php

namespace MA\Entity\Comment;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use MA\Entity\HintReasonInterface,
	MA\Entity\AbstractComment;

/**
 * @ORM\Entity()
 */
class HintReason extends AbstractComment
{
	/**
	 * @var PostComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Comment\HintReason",
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
	 *	targetEntity = "MA\Entity\Comment\HintReason",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintReason",
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
     * @return HintReasonInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param HintReasonInterface source the value to set.
     * @return HintReason.
     */
    public function setSource(HintReasonInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get hint.
     *
     * @return HintReason.
     */
    public function getReason()
    {
        return $this->getSource();
    }
    
    /**
     * Set hint.
     *
     * @param HintReason hint the value to set.
     * @return HintReason.
     */
    public function setReason(HintReasonInterface $hint)
    {
        $this->setSource($hint);
        return $this;
    }
}
