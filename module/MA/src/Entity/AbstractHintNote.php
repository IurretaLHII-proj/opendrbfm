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
	 * @var SimulationInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Simulation",
	 *	inversedBy	 = "notes",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id",
	 *  nullable = true
	 * )
	 */
	protected $simulation;
    
    /**
     * Set simulation.
     *
     * @param SimulationInterface|null simulation the value to set.
     * @return Note.
     */
    public function setSimulation(\MA\Entity\SimulationInterface $simulation = null)
    {
        $this->simulation = $simulation;
        return $this;
    }
    
    /**
     * Get simulation.
     *
     * @return SimulationInterface|null.
     */
    public function getSimulation()
    {
        return $this->simulation;
    }
}
