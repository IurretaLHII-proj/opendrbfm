<?php

namespace MA\Entity;

class HintReasonRel implements \JsonSerializable
{
    protected $reason;

	public function __construct(HintReasonInterface $reason)
	{
		$this->reason = $reason;
	}

    /**
	 * TODO
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            'id'     => $this->reason->getId(),
			'hint'   => $this->reason->getHint(),
			'stage'  => $this->reason->getHint()->getStage(),
        );
    }
}
