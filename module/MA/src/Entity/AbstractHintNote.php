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

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'edit',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
