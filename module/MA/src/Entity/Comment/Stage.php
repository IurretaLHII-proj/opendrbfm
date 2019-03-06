<?php

namespace MA\Entity\Comment;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use MA\Entity\StageInterface,
	MA\Entity\AbstractComment;

/**
 * @ORM\Entity()
 */
class Stage extends AbstractComment
{
	/**
	 * @var PostComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Comment\Stage",
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
	 *	targetEntity = "MA\Entity\Comment\Stage",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Stage",
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
     * @return StageInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param StageInterface source the value to set.
     * @return Stage.
     */
    public function setSource(StageInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get version.
     *
     * @return Stage.
     */
    public function getStage()
    {
        return $this->getSource();
    }
    
    /**
     * Set version.
     *
     * @param StageInterface version the value to set.
     * @return Stage.
     */
    public function setStage(StageInterface $version)
    {
        $this->setSource($version);
        return $this;
    }
}
