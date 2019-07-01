<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\StageInterface;

class HintService extends AbstractService
{
    /**
     * @param EventInterface $e
     */
    public function cloneHints(EventInterface $e)
    {
        $stage  = $e->getTarget();
        $params = $e->getParams();

        if (!(array_key_exists('origin', $params) && $params['origin'] instanceof StageInterface)) {
            throw new \InvalidArgumentException(sprintf("Clone origin parameter missing"));
        }

        foreach ($params['origin']->getHints() as $hint) {
            $_hint = clone $hint;
            $stage->addHint($_hint);
            $this->triggerService($e->getName(), $_hint, ['origin' => $hint]);
        }
    }
}
