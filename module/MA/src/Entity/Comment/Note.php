<?php

namespace MA\Entity\Comment;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use JsonSerializable;
use DateTime;

use MA\Entity\NoteInterface,
	MA\Entity\AbstractNote,
	MA\Entity\AbstractComment;

/**
 * @ORM\Entity()
 */
class Note extends AbstractComment
{
	/**
	 * @var PostComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Comment\Note",
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
	 *	targetEntity = "MA\Entity\Comment\Note",
	 *	mappedBy	 = "parent",
	 *	cascade = {"remove"}
	 * )
	 * @ORM\OrderBy({"created" = "ASC"})
	 */
	protected $children;

	/**
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\AbstractNote",
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
     * @return NoteInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param NoteInterface source the value to set.
     * @return Note.
     */
    public function setSource(NoteInterface $source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Get hint.
     *
     * @return Note.
     */
    public function getNote()
    {
        return $this->getSource();
    }
    
    /**
     * Set hint.
     *
     * @param NoteInterface hint the value to set.
     * @return Note.
     */
    public function setNote(NoteInterface $hint)
    {
        $this->setSource($hint);
        return $this;
    }
}
