<?php

namespace MA\Entity\Image;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(
 *   name="image",
 * )
 */
class IStage extends \MA\Entity\AbstractImage
{
    /**
     * @ORM\ManyToOne(
     *     targetEntity="MA\Entity\Stage",
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
     * @return Stage.
     */
    public function getSource()
    {
        return $this->source;
    }
    
    /**
     * Set source.
     *
     * @param Stage|null source the value to set.
     * @return vartype.
     */
    public function setSource(\MA\Entity\Stage $source = null)
    {
        $this->source = $source;
        return $this;
    }
}
