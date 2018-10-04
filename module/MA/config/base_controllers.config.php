<?php

namespace MA;

return [
	Controller\ProcessController::class => [
		'repository' => Entity\Process::class,
	],
	Controller\Js\ProcessController::class => [
		'repository' => Entity\Process::class,
	],
	Controller\Js\StageController::class => [
		'repository' => Entity\Stage::class,
	],
	Controller\ImageController::class => [
		'repository' => Entity\Image\IStage::class,
	],
];
