<?php

namespace MA\Entity;

interface NoteInterface
{

	/**
	 * @return ProcessInterface
	 */
	public function getProcess();

	/**
	 * @return VersionInterface 
	 */
	public function getVersion();

	/**
	 * @return StageInterface 
	 */
	public function getStage();

	/**
	 * @return HintInterface 
	 */
	public function getHint();
}
