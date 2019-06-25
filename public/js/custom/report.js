App.controller('_ProcessReportCtrl', function($scope, $resource) {

	$scope.init = function(item, values) {
		$scope.values  = {
			versions: [],
			stages: [],
			hints: [],
			reasons: [],
			influences: [],
			simulations: [],
			notes: []
		};
		$scope.process = MAProcess.fromJSON(item);
		$scope.process.versions.forEach(v => {
			//$scope.values.versions.push(v.id);
			v.stages.forEach(s => {
				//$scope.values.stages.push(s.id);
			});
		});
		$scope.selection = angular.copy($scope.values);
		console.log($scope.process, $scope.values);
	}

	$scope.toggleVersion = function(version) {
		if ($scope.isVersionSelected(version)) {
			$scope.unselect(version);
		}
		else {
			$resource(version.links.getHref('stages')).get().$promise.then(
				function(data) {
					version.stages = [];
					angular.forEach(data._embedded.items, e => {
						let stage = MAStage.fromJSON(e);
						version.addStage(stage);
						$scope.toggleStage(stage);
					});
					$scope.selection.versions.push(version.id);
				}
			);
		}
	}

	$scope.toggleStage = function(stage) {
		if ($scope.isStageSelected(stage)) {
			$scope.unselect(stage);
		}
		else {
			$resource(stage.links.getHref('hints')).get().$promise.then(
				function(data) {
					stage.hints = [];
					angular.forEach(data._embedded.items, e => {
						let hint = MAHint.fromJSON(e);
						stage.addHint(hint)
						$scope.select(hint);
					});
					$scope.selection.stages.push(stage.id);
				}
			);
		}
	}

	$scope.toggleHint = function(item) {$scope.isHintSelected(item) ? $scope.unselect(item) : $scope.select(item);}
	$scope.toggleReason = function(item) {$scope.isReasonSelected(item) ? $scope.unselect(item) : $scope.select(item);}
	$scope.toggleInfluence = function(item) {$scope.isInfluenceSelected(item) ? $scope.unselect(item) : $scope.select(item);}
	$scope.toggleSimulation = function(item) {$scope.isSimulationSelected(item) ? $scope.unselect(item) : $scope.select(item);}
	$scope.toggleNote = function(item) {$scope.isNoteSelected(item) ? $scope.unselect(item) : $scope.select(item);}

	$scope.select = function(item) {
		if (item instanceof MAVersion) {
			$scope.selection.versions.push(item.id);
			item.stages.forEach(i => $scope.select(i));
		}
		else if (item instanceof MAStage) {
			$scope.selection.stages.push(item.id);
			item.hints.forEach(i => $scope.select(i));
		}
		else if (item instanceof MAHint) {
			$scope.selection.hints.push(item.id);
			item.reasons.forEach(i => $scope.select(i));
		}
		else if (item instanceof MAHintReason) {
			$scope.selection.reasons.push(item.id);
			item.notes.forEach(i => $scope.select(i));
			item.influences.forEach(i => $scope.select(i));
		}
		else if (item instanceof MAHintInfluence) {
			$scope.selection.influences.push(item.id);
			item.notes.forEach(i => $scope.select(i));
			item.simulations.forEach(i => $scope.select(i));
		}
		else if (item instanceof MASimulation) {
			$scope.selection.simulations.push(item.id);
			item.suggestions.forEach(i => $scope.select(i));
			item.effects.forEach(i => $scope.select(i));
			item.preventions.forEach(i => $scope.select(i));
		}
		else if (item instanceof MANote) {
			$scope.selection.notes.push(item.id);
		}
	}

	$scope.unselect = function(item) {
		if (item instanceof MAVersion) {
			$scope.selection.versions.splice($scope.selection.versions.indexOf(item.id), 1);
			item.stages.forEach(i => $scope.unselect(i));
		}
		else if (item instanceof MAStage) {
			$scope.selection.stages.splice($scope.selection.stages.indexOf(item.id), 1);
			item.hints.forEach(i => $scope.unselect(i));
		}
		else if (item instanceof MAHint) {
			$scope.selection.hints.splice($scope.selection.hints.indexOf(item.id), 1);
			item.reasons.forEach(i => $scope.unselect(i));
		}
		else if (item instanceof MAHintReason) {
			$scope.selection.reasons.splice($scope.selection.reasons.indexOf(item.id), 1);
			item.notes.forEach(i => $scope.unselect(i));
			item.influences.forEach(i => $scope.unselect(i));
		}
		else if (item instanceof MAHintInfluence) {
			$scope.selection.influences.splice($scope.selection.influences.indexOf(item.id), 1);
			item.notes.forEach(i => $scope.unselect(i));
			item.simulations.forEach(i => $scope.unselect(i));
		}
		else if (item instanceof MASimulation) {
			$scope.selection.simulations.splice($scope.selection.simulations.indexOf(item.id), 1);
			item.suggestions.forEach(i => $scope.unselect(i));
			item.effects.forEach(i => $scope.unselect(i));
			item.preventions.forEach(i => $scope.unselect(i));
		}
		else if (item instanceof MANote) {
			$scope.selection.notes.splice($scope.selection.notes.indexOf(item.id), 1);
		}
	}

	$scope.isVersionSelected 	= function(item) {return $scope.selection.versions.indexOf(item.id)>-1}
	$scope.isStageSelected 		= function(item) {return $scope.selection.stages.indexOf(item.id)>-1}
	$scope.isHintSelected 		= function(item) {return $scope.selection.hints.indexOf(item.id)>-1}
	$scope.isReasonSelected 	= function(item) {return $scope.selection.reasons.indexOf(item.id)>-1}
	$scope.isInfluenceSelected  = function(item) {return $scope.selection.influences.indexOf(item.id)>-1}
	$scope.isSimulationSelected = function(item) {return $scope.selection.simulations.indexOf(item.id)>-1}
	$scope.isNoteSelected 		= function(item) {return $scope.selection.notes.indexOf(item.id)>-1}
});
