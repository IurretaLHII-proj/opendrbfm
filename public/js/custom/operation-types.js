App.controller('_OperationTypesCtrl', function($scope, $resource, $uibModal) {
	$scope.collection = [];
	$scope.init = function(collection) {
		angular.forEach(collection._embedded.items, function(item, index) {
			let group = MAOperationType.fromJSON(item);
			$scope.collection.push(group); 
		});
	};

	$scope.deleteGroup = function(group) {
		var war = $scope._addWarning("Deleting...");
		$resource(group.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		$scope.collection.splice($scope.collection.indexOf(group), 1);
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

	$scope.editGroup = function(group) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-type-form.html',
			controller: '_OperationTypeModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {group: group}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.addOperation = function(type) {
		let op = new MAOperation;
		op.type = type;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {op:op}
		});

		modal.result.then(
			function(res) {
				type.addOperation(op);
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.editOperation = function(op) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {op:op}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.replaceOperation = function(op) {
		console.log($scope.collection);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-replace-form.html',
			controller: '_OperationReplaceModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {op:op}
		});

		modal.result.then(
			function(res) {
        		op.type.removeOperation(op);
				$scope.addSuccess("Operation deleted succesfully");
			},
			function(err) {}
		);
	}

	$scope.showHints = function(op) {
		if (!op._sh) {
			op.hints = [];
			$resource(op.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items, item => {
						op.addHint(MAHintType.fromJSON(item));
					});
					op._sh=true;
				});
		}
		op._sh = !op._sh;
	};
});

App.controller('_OperationReplaceModalCtrl', function($scope, $uibModalInstance, $resource, op)
{
	$scope.operation  = op;
	$scope.dfltRplc	  = {id:null,  name:" --Select operation"};
	$scope.operations = [$scope.dfltRplc];
	$scope.groups 	  = [{id:null, name:" --Select group-- "}].concat($scope.collection);
	$scope.values 	  = {
		operationType: null,
		operation: 	   null,
	}
	$scope.groupChanged = function() {
		$scope.operations = [$scope.dfltRplc];
		if ($scope.values.operationType) {
			let group = $scope.groups.find(e => {return $scope.values.operationType == e.id});
			$scope.operations = $scope.operations.concat(group.operations);
			var current = $scope.operations.find(e => {return $scope.operation.id == e.id});
			if (current) $scope.operations.splice($scope.operations.indexOf(current), 1);
		}
	}

	$scope.replace = function() {
		var war = $scope._addWarning("Replacing...");
		$resource($scope.operation.links.getHref('replace')).save($scope.values).$promise.then(
			function (data) {
				$scope._closeWarning(war);
				$uibModalInstance.close();	
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.errors = err.data.errors;
			}	
		);
	}

	$scope.delete = function() {
		var war = $scope._addWarning("Deleting...");
		$resource($scope.operation.links.getHref('delete')).delete().$promise.then(
			function (data) {
				$scope._closeWarning(war);
				$uibModalInstance.close();	
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};

});
