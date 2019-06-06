App.controller('_ProcessPdfCtrl', function($scope, $resource) {

	$scope.init = function(item, values) {
		$scope.process = MAProcess.fromJSON(item);
	}

	$scope.versionChanged = function(version) {
		if (version.checked && !version.isStagesLoaded()) {
			$resource(version.links.getHref('stages')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items, e => {
						let stage = MAStage.fromJSON(e);
						version.addStage(stage)
						$resource(stage.links.getHref('hints')).get().$promise.then(
							function(data) {
								angular.forEach(data._embedded.items, e => {
									let hint = MAHint.fromJSON(e);
									stage.addHint(hint)
								});
								stage.hintsLoaded = true;
								version.stages.forEach(stage => {
									stage.checked = version.checked;
									$scope.stageChanged(stage); 
								});
							}
						);
					});
					version.stagesLoaded = true;
				}
			);
		}
		else {
			version.stages.forEach(stage => {
				stage.checked = version.checked;
				$scope.stageChanged(stage); 
			});
		}
	}

	$scope.stageChanged = function(stage) {
		stage.hints.forEach(hint => {
			hint.checked = stage.checked;
			$scope.hintChanged(hint)
		});
	}
	$scope.hintChanged = function(hint) {
		hint.reasons.forEach(reason => {
			reason.checked = hint.checked;
			$scope.reasonChanged(reason)
		});
	}
	$scope.reasonChanged = function(reason) {
		reason.influences.forEach(influence => {
			influence.checked = reason.checked;
			$scope.influenceChanged(influence)
		});
	}
	$scope.influenceChanged = function(influence) {
		influence.simulations.forEach(simulation => {
			simulation.checked = influence.checked;
			$scope.simulationChanged(simulation);
		});
	}
	$scope.simulationChanged = function(simulation) {
		simulation.suggestions.forEach(e => e.checked = simulation.checked);
		simulation.effects.forEach(e => e.checked = simulation.checked);
		simulation.preventions.forEach(e => e.checked = simulation.checked);
	}

});
