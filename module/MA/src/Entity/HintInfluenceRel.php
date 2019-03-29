<?php

namespace MA\Entity;

class HintInfluenceRel implements \JsonSerializable
{
    protected $influence;

	public function __construct(HintInfluenceInterface $influence)
	{
		$this->influence = $influence;
	}

    /**
	 * TODO
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            'id'     => $this->influence->getId(),
			'reason' => new HintReasonRel($this->influence->getReason()), 
        );
    }
}
