<?php

namespace MA\Entity\Comment;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use MA\Entity\VersionInterface,
	MA\Entity\AbstractComment;

/**
 * @ORM\Entity()
 */
class Version extends AbstractComment
{
	/**
	 * @var PostComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Comment\Version",
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
	 *	targetEntity = "MA\Entity\Comment\Version",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Version",
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
     * @return VersionInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param VersionInterface source the value to set.
     * @return Version.
     */
    public function setSource(VersionInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get version.
     *
     * @return Version.
     */
    public function getVersion()
    {
        return $this->getSource();
    }
    
    /**
     * Set version.
     *
     * @param VersionInterface version the value to set.
     * @return Version.
     */
    public function setVersion(VersionInterface $version)
    {
        $this->setSource($version);
        return $this;
    }
}
