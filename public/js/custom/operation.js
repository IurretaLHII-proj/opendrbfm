
App.controller('_OperationCtrl', function($scope, $uibModal, $resource) {

	$scope.init = function(item, values) {
		$scope.entity = MAOperation.fromJSON(item);
		$resource($scope.entity.links.getHref('hints')).get().$promise.then(
			function(data) {
				angular.forEach(data._embedded.items, item => {
					$scope.entity.addHint(MAHintType.fromJSON(item));
				});
				$resource('/process/material/json').get().$promise.then(
					function(data){
						angular.forEach(data._embedded.items, item => {
							$scope.materials.push(MAMaterial.fromJSON(item));
						});
						$resource('/process/version/type/json').get().$promise.then(
							function(data){
								angular.forEach(data._embedded.items, item => {
									$scope.types.push(MAVersionType.fromJSON(item));
								});
								$scope.search();
							},
							function(err){})
					},
					function(err) {});
			},
			function(err) {
			}
		);
	}

	$scope.search = function() {
		$scope.stages = new MACollection();
		$resource($scope.entity.links.getHref('stages')).get($scope.getQuery()).$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return EMAStage.fromJSON(e)});
				$scope.stages.load(data);
			});
	}

	$scope.more = function() {
		$resource($scope.stages.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return EMAStage.fromJSON(e)});
				$scope.stages.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.editOperation = function() {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {op:$scope.entity}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.addHintType = function() {
		let type = new MAHintType();
		type.operation = $scope.entity;
		var modal = $uibModal.open({
			templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
			controller: '_HintTypeModalCtrl',	
			size: 'lg',
			resolve: {type: type},
		});

		modal.result.then(
			function(res) {
				$scope.entity.addHint(type);
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.editHintType = function(hint) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
			controller: '_HintTypeModalCtrl',	
			size: 'lg',
			resolve: {type: hint} 
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.deleteHintType = function(hint) {
		var war = $scope._addWarning("Deleting...");
		$resource(hint.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		$scope.entity.hints.splice($scope.entity.hints.indexOf(hint), 1);
				$scope._closeWarning(war);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.order		= 'created';
	$scope.criteria		= 'DESC';
	$scope.materials 	= [{id:null, name:"--- ANY ---"}];
	$scope.types 		= [{id:null, name:"--- ANY ---"}];
	$scope.states		= [
		{id:null, 					name: "--- ANY ---"},
		{id:MAVersion.STATE_IN_PROGRESS, 	name: MAVersion.stateLabel(MAVersion.STATE_IN_PROGRESS)},
		{id:MAVersion.STATE_APPROVED,  		name: MAVersion.stateLabel(MAVersion.STATE_APPROVED)},
		{id:MAVersion.STATE_CANCELLED, 		name: MAVersion.stateLabel(MAVersion.STATE_CANCELLED)},
	];
	$scope.state  	 = $scope.states[0];
	$scope.material  = $scope.materials[0];
	$scope.type 	 = $scope.types[0];
	$scope.selOrder		= function(value) { $scope.order	= value; }
	$scope.selCriteria	= function(value) { $scope.criteria	= value; }
	$scope.selType 		= function(value) { $scope.type		= value; }
	$scope.selMaterial	= function(value) { $scope.material = value; }
	$scope.selState		= function(value) { $scope.state 	= value; }
	$scope.getQuery		= function() {
		let query = {};
		if ($scope.material.id != null)  query.material = $scope.material.id;
		if ($scope.type.id != null) 	 query.type 	= $scope.type.id;
		if ($scope.state.id != null) 	 query.state    = $scope.state.id;
		if ($scope.process != null) 	 query.process  = $scope.process.id;
		query.prior    = $scope.priority;
		query.order    = $scope.order;
		query.criteria = $scope.criteria;
		return query;
	}
});
