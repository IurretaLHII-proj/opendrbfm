<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class Process extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var ProcessInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Process",
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
     * @param ProcessInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\ProcessInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return ProcessInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
}
