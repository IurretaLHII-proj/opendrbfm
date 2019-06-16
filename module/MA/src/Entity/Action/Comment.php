<?php

namespace MA\Entity\Action;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection;
use DateTime;

/**
 * @ORM\Entity()
 */
class Comment extends \MA\Entity\AbstractProcessAction
{
	/**
	 * @var AbstractComment
	 * @ORM\ManyToOne(
	 *	targetEntity = "MA\Entity\AbstractComment",
	 *	inversedBy	 = "actions",
	 * )
	 * @ORM\JoinColumn(
	 *	name= "src_id",
	 *	referencedColumnName = "id"
	 * )
	 */
	protected $source;
    
    /**
     * Set source.
     *
     * @param AbstractComment source the value to set.
     * @return Action.
     */
    public function setSource(\MA\Entity\AbstractComment $source)
    {
        $this->source = $source;
        return $this;
    }
    
    /**
     * Get source.
     *
     * @return AbstractComment.
     */
    public function getSource()
    {
        return $this->source;
    }

	/**
	 * TODO
	 * @inheritDoc
	 */
	public function jsonSerialize()
	{
		$json    = parent::jsonSerialize();
		$comment = $this->getSource();
		switch (true) {
			case $comment instanceof \MA\Entity\Comment\Version:
				$json['version'] = $comment->getSource();
				break;
			case $comment instanceof \MA\Entity\Comment\Stage:
				$json['stage']   = $comment->getSource();
				$json['version'] = $comment->getSource()->getVersion();
				break;
			case $comment instanceof \MA\Entity\Comment\Hint:
				$json['hint']    = $comment->getSource();
				$json['stage']   = $comment->getSource()->getStage();
				$json['version'] = $comment->getSource()->getStage()->getVersion();
				break;
			case $comment instanceof \MA\Entity\Comment\HintReason:
			case $comment instanceof \MA\Entity\Comment\HintInfluence:
			case $comment instanceof \MA\Entity\Comment\Simulation:
				$json['hint']    = $comment->getSource()->getHint();
				$json['stage']   = $comment->getSource()->getStage();
				$json['version'] = $comment->getSource()->getVersion();
				break;
			case $comment instanceof \MA\Entity\Comment\Note:
				$note = $comment->getSource();
				switch (true) {
					case $note instanceof \MA\Entity\Note\HintReason:
						$source = $note->getReason();
						break;
					case $note instanceof \MA\Entity\Note\HintInfluence:
						$source = $note->getInfluence();
						break;
					case $note instanceof \MA\Entity\AbstractHintNote:
						$source = $note->getSimulation();
						break;
					default:
						throw new \InvalidArgumentException(sprinf("%s not defined", get_class($note)));
				}
				$json['hint']    = $source->getHint();
				$json['stage']   = $source->getStage();
				$json['version'] = $source->getVersion();
				break;
		}
		return $json;
	}
}
