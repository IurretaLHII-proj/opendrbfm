<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class Note extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var NoteInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\AbstractNote",
	 *	inversedBy	 = "actions",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $source;
    
    /**
     * Set source.
     *
     * @param NoteInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\NoteInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return NoteInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
}
