<?php

namespace MA\InputFilter;

use Zend\InputFilter\InputFilter;

class SimulationInputFilter extends InputFilter
{
	/**
	 * @inheritDoc
	 */
	public function isValid($context = null)
	{
		if (\MA\Entity\Simulation::STATE_CREATED >= ($state = (int) $this->getValue('state'))) {
			$keys = array_keys($this->getInputs());
		    unset($keys[array_search('who', $keys)]);
		    unset($keys[array_search('when', $keys)]);
			$this->setValidationGroup($keys);
		}
		return parent::isValid($context);
	}

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->add(
			[
                'name' => 'id',
				'required' => false,
			],
            'id'
        );

        $this->add(['name' => 'state',], 'state');
        $this->add(['name' => 'who',], 'who');
        $this->add(['name' => 'when',], 'when');

        parent::init();
    }
}
