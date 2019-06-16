<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use DateTime;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass = "MA\Doctrine\Repository\NotificationRepository")
 * @ORM\Table(name="notification")
 */
class Notification implements 
	JsonSerializable,
	ResourceInterface, 
	\User\Entity\UserAwareInterface,
	\Base\Hal\LinkProvider
{
	/**
	 * @var int 
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var User
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\User",
	 *	inversedBy	 = "notifications",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "sid",
	 *	referencedColumnName = "user_id"
	 * )
	 */
	protected $subscriber;

	/**
	 * @var AbstractProcessAction 
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\AbstractProcessAction",
	 *	inversedBy	 = "notifications",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "a_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $action;

	/**
	 * @var string 
	 * @ORM\Column(type="boolean", name="r", options="{default:false}")
	 */
	protected $readed = false;

	public function __construct()
	{
	}
    
    /**
     * Get id.
     *
     * @return integer.
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set id.
     *
     * @param integer id the value to set.
     * @return Notification.
     */
    public function setId($id)
    {
        $this->id = (int) $id;
        return $this;
    }
    
    /**
     * Get subscriber.
     *
     * @return UserInterface.
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }
    
    /**
     * Set subscriber.
     *
     * @param UserInterface subscriber the value to set.
     * @return Notification.
     */
    public function setSubscriber(UserInterface $subscriber)
    {
        $this->subscriber = $subscriber;
        return $this;
    }

	/**
	 * @inheritDoc
	 */
	public function setUser(\User\Entity\UserInterface $user)
	{
		$this->setSubscriber($user);
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getUser()
	{
		return $this->getSubscriber();
	}
    
    /**
     * Get action.
     *
     * @return AbstractProcessAction.
     */
    public function getAction()
    {
        return $this->action;
    }
    
    /**
     * Set action.
     *
     * @param AbstractProcessAction action the value to set.
     * @return Notification.
     */
    public function setAction(AbstractProcessAction $action)
    {
        $this->action = $action;
        return $this;
    }
    
    /**
     * Get readed.
     *
     * @return boolean.
     */
    public function isReaded()
    {
        return $this->readed;
    }
    
    /**
     * Set readed.
     *
     * @param bolean readed the value to set.
     * @return Process.
     */
    public function setReaded($readed = false)
    {
        $this->readed = (boolean) $readed;
        return $this;
    }

	/**
	 * @inheritDoc
	 */
	public function getResourceId()
	{
		return self::class;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		$json = $this->getAction()->jsonSerialize();
		$json['readed'] = $this->isReaded();
		return $json;
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'read',
				'privilege'   => $this->isReaded() ? false : 'read',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/notification/detail/json',
				    'params'  => ['action' => 'read', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/notification/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
		];
	}
}
