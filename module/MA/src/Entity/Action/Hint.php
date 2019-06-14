<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class Hint extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var HintInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Hint",
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
     * @param HintInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\HintInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return HintInterface.
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
		$json['hint']    = $this->getSource();
		$json['stage']   = $this->getSource()->getStage();
		$json['version'] = $this->getSource()->getVersion();
		return $json;
	}
}
