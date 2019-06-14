<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class Version extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var VersionInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Version",
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
     * @param VersionInterface source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\VersionInterface $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return VersionInterface.
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
		$json['version'] = $this->getSource();
		return $json;
	}
}
