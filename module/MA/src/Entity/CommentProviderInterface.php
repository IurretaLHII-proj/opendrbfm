<?php

namespace MA\Entity;

interface CommentProviderInterface
{
	/**
	 * getComments.
	 *
	 * @return CommentInterface[]
	 */
	public function getComments();

	/**
	 * setComments.
	 *
	 * @param CommentInterface[] $comments
	 */
	public function setComments($comments);

	/**
	 * increaseCommentCount.
	 *
	 * @return CommentProvicerInterface
	 */
	public function increaseCommentCount();

	/**
	 * decreaseCommentCount.
	 *
	 * @return CommentProvicerInterface
	 */
	public function decreaseCommentCount();
    
    /**
     * Get commentCount.
     *
     * @return int.
     */
    public function getCommentCount();
    
    /**
     * Set commentCount.
     *
     * @param int commentCount the value to set.
     * @return CommentProviderInterface.
     */
    public function setCommentCount($commentCount);
}
