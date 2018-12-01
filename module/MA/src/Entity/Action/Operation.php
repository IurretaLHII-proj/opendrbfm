<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class Operation extends \MA\Entity\AbstractUserAction
{
	/**
	 * @var OperationInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Operation",
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
     * @param OperationInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\OperationInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return OperationInterface.
     */
    public function getSource()
    {
        return $this->source;
    }
}
