<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class Simulation extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var SimulationInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Simulation",
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
     * @param SimulationInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\SimulationInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return SimulationInterface.
     */
    public function getSource()
    {
        return $this->source;
    }

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		$json = parent::jsonSerialize();
		$json['hint']    = $this->getSource()->getHint();
		$json['stage']   = $this->getSource()->getStage();
		$json['version'] = $this->getSource()->getVersion();
		return $json;
	}
}
