<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 * @ORM\Table(name="process_action")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 * 	"process" 	= "MA\Entity\Action\Process",
 * 	"version"  	= "MA\Entity\Action\Version",
 * 	"stage"   	= "MA\Entity\Action\Stage",
 * 	"hint"    	= "MA\Entity\Action\Hint",
 * 	"reason"    = "MA\Entity\Action\HintReason",
 * 	"influence" = "MA\Entity\Action\HintInfluence",
 * 	"simulation"= "MA\Entity\Action\Simulation",
 * 	"note"		= "MA\Entity\Action\Note",
 * 	"comment"	= "MA\Entity\Action\Comment",
 * })
 */
abstract class AbstractProcessAction extends AbstractAction
{
	/**
	 * @var ProcessInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\Process",
	 *	inversedBy	 = "actions",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "pcs_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $process;

	/**
	 * @var Notification[]
	 * @ORM\OneToMany(
     *  targetEntity = "MA\Entity\Notification",
	 *	mappedBy	 = "action",
	 * )
	 */
	protected $notifications;

	public function __construct()
	{
		$this->notifications = new ArrayCollection;
	}

	/**
	 * @param Notification[] $notifications
	 * @return AbstractProcessAction
	 */
	public function setNotification($notifications)
	{
		$this->notifications = $notifications;
		return $this;
	}

	/**
	 * @return Notification
	 */
	public function getNotifications()
	{
		return $this->notifications;
	}
    
    /**
     * Set process.
     *
     * @param ProcessInterface process the value to set.
     * @return Action.
     */
    public function setProcess(\MA\Entity\ProcessInterface $process)
    {
        $this->process = $process;
        return $this;
    }
    
    /**
     * Get process.
     *
     * @return ProcessInterface.
     */
    public function getProcess()
    {
        return $this->process;
    }

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		$json = parent::jsonSerialize();
		$json['process'] = $this->getProcess();
		return $json;
	}
}
