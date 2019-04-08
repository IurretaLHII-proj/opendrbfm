<?php

namespace MA\Entity\Note;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HintReason extends \MA\Entity\AbstractNote
{
	/**
	 * @var HintReasonInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintReason",
	 *	inversedBy	 = "notes",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id",
	 *  nullable = true
	 * )
	 */
	protected $reason;
    
    /**
     * Set reason.
     *
     * @param HintReasonInterface|null reason the value to set.
     * @return Note.
     */
    public function setReason(\MA\Entity\HintReasonInterface $reason = null)
    {
        $this->reason = $reason;
        return $this;
    }
    
    /**
     * Get reason.
     *
     * @return HintReasonInterface|null.
     */
    public function getReason()
    {
        return $this->reason;
    }

	/**
	 * @inheritDoc
	 */
	public function getProcess()
	{
		if ($this->getReason() !== null) {
			return $this->getReason()->getProcess();
		}
	}

	/**
  	 * @inheritDoc
  	 */
  	public function provideLinks()
  	{
		return [
			[
				'rel'   	  => 'edit',
				'privilege'   => 'edit',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'edit', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'delete',
				'privilege'   => 'delete',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'delete', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comment',
				'privilege'   => 'comment',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'comment', 'id' => $this->getId()],
				],
			],
			[
				'rel'   	  => 'comments',
				'privilege'   => 'comments',
				'resource'	  => $this,
				'route' => [
				    'name'    => 'process/note/detail/json',
				    'params'  => ['action' => 'comments', 'id' => $this->getId()],
				],
			],
		];
	}
}
