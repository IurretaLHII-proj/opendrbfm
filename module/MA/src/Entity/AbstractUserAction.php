<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="action")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 * 	"op" = "MA\Entity\Action\Operation",
 * })
 */
abstract class AbstractUserAction extends \MA\Entity\AbstractAction
{
}
