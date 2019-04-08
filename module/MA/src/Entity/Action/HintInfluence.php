<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class HintInfluence extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var HintInfluenceInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintInfluence",
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
     * @param HintInfluenceInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\HintInfluenceInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return HintInfluenceInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
}
