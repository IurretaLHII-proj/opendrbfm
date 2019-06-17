App.controller('_ProcessReportCtrl', function($scope, $resource) {

	$scope.init = function(item, values) {
		$scope.values  = {
			versions: [],
			stages: [],
			hints: [],
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
		var index = $scope.selection.versions.indexOf(version.id);
		if (index > -1) {
			$scope.selection.versions.splice(index, 1);
		}
		else {
			$scope.selection.versions.push(version.id);
			$resource(version.links.getHref('stages')).get().$promise.then(
				function(data) {
					version.stages = [];
					angular.forEach(data._embedded.items, e => {
						let stage = MAStage.fromJSON(e);
						version.addStage(stage)
							$scope.toggleStage(stage);
					});
				}
			);
		}
	}

	$scope.toggleStage = function(stage) {
		var index = $scope.selection.stages.indexOf(stage.id);
		if (index > -1) {
			$scope.selection.stages.splice(index, 1);
		}
		else {
			$scope.selection.stages.push(stage.id);
			$resource(stage.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items, e => {
						let hint = MAHint.fromJSON(e);
						stage.addHint(hint)
					});
				}
			);
		}
	}

	$scope.toggleHint = function(hint) {
		var index = $scope.selection.hints.indexOf(hint.id);
		if (index > -1) {
			$scope.selection.hints.splice(index, 1);
		}
		else {
			$scope.selection.hints.push(hint.id);
		}
	}

});
