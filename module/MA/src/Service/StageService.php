<?php

namespace MA\Service;

use Zend\EventManager\EventInterface;
use Base\Service\AbstractService;
use MA\Entity\VersionInterface;

class StageService extends AbstractService
{
    /**
     * @param EventInterface $e
     */
    public function cloneStages(EventInterface $e)
    {
        $version = $e->getTarget();
        $params  = $e->getParams();

        if (!(array_key_exists('origin', $params) && $params['origin'] instanceof VersionInterface)) {
            throw new \InvalidArgumentException(sprintf("Clone origin parameter missing"));
        }

        foreach ($params['origin']->getStages() as $stage) {
            $_stage = clone $stage;
            $this->triggerService($e->getName(), $_stage, ['origin' => $stage]);
            $version->addStage($_stage);

            foreach ($stage->getImages() as $image) {
                $_image = clone $image;
                $this->triggerService($e->getName(), $_image);
                $_stage->addImage($_image);
            }
        }
    }
}
