<?php

namespace MA\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
abstract class AbstractContextNote extends AbstractNote implements
	CommentProviderInterface,
   	\Base\Hal\LinkProvider
{
	/**
	 * @var HintContextInterface
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\HintContext",
	 *	inversedBy	 = "notes",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id",
	 *  nullable = true
	 * )
	 */
	protected $context;
    
    /**
     * Set context.
     *
     * @param HintContextInterface|null context the value to set.
     * @return Note.
     */
    public function setContext(\MA\Entity\HintContextInterface $context = null)
    {
        $this->context = $context;
        return $this;
    }
    
    /**
     * Get context.
     *
     * @return HintContextInterface|null.
     */
    public function getContext()
    {
        return $this->context;
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
