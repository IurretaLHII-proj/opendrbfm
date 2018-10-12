<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
abstract class AbstractProcessAction extends AbstractAction
{
	/**
	 * @var ProcessInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Process",
	 *	inversedBy	 = "actions",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "pcs_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $process;
    
    /**
     * Set process.
     *
     * @param ProcessInterface process the value to set.
     * @return Action.
     */
    public function setProcess(\MA\Entity\ProcessInterface $process)
    {
        $this->process = $process;
        return $this;
    }
    
    /**
     * Get process.
     *
     * @return ProcessInterface.
     */
    public function getProcess()
    {
        return $this->process;
    }
}
