<?php

namespace MA\Entity\Note;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class HintInfluence extends \MA\Entity\AbstractNote
{
	/**
	 * @var HintInfluenceInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintInfluence",
	 *	inversedBy	 = "notes",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $influence;
    
    /**
     * Set influence.
     *
     * @param HintInfluenceInterface influence the value to set.
     * @return Note.
     */
    public function setInfluence(\MA\Entity\HintInfluenceInterface $influence)
    {
        $this->influence = $influence;
        return $this;
    }
    
    /**
     * Get influence.
     *
     * @return HintInfluenceInterface|null.
     */
    public function getInfluence()
    {
        return $this->influence;
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
