<?php

namespace MA\Entity;

class HintContextRel implements \JsonSerializable
{
    protected $context;

	public function __construct($context)
	//public function __construct(HintContext $context)
	{
		$this->context = $context;
	}

    /**
	 * TODO
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return array(
            'id'     => $this->context->getId(),
			'hint'   => $this->context->getHint(),
			'stage'  => $this->context->getHint()->getStage(),
        );
    }
}
