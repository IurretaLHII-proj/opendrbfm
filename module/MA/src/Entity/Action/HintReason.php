<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class HintReason extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var HintReasonInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintReason",
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
     * @param HintReasonInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\HintReasonInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return HintReasonInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
}
