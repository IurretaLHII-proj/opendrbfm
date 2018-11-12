<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
abstract class AbstractHintNote extends AbstractNote
{
	/**
	 * @var HintInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Hint",
	 *	inversedBy	 = "notes",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id",
	 *  nullable = true
	 * )
	 */
	protected $hint;
    
    /**
     * Set hint.
     *
     * @param HintInterface|null hint the value to set.
     * @return Note.
     */
    public function setHint(\MA\Entity\HintInterface $hint = null)
    {
        $this->hint = $hint;
        return $this;
    }
    
    /**
     * Get hint.
     *
     * @return HintInterface|null.
     */
    public function getHint()
    {
        return $this->hint;
    }
}
