<?php

namespace MA\Entity\Image;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 */
class ISimulation extends \MA\Entity\AbstractImage
{
    /**
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Simulation",
     *     inversedBy="images"
     * )
     * @ORM\JoinColumn(
     *     name = "src_id",
	 *     referencedColumnName = "id",
	 *	   nullable = true
     * )
     */
    protected $source;
    
    /**
     * Get source.
     *
     * @return Simulation.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param Simulation|null source the value to set.
     * @return vartype.
     */
    public function setSource(\MA\Entity\Simulation $source = null)
    {
        $this->source = $source;
        return $this;
    }
}
