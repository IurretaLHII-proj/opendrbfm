App.controller('_MaterialCollectionCtrl', function($scope, $resource, $uibModal, $window) {

	$scope.name			= "";
	$scope.order		= "text";
	$scope.criteria		= "DESC";
	$scope.selOrder		= function(value) { $scope.order	= value; }
	$scope.selCriteria	= function(value) { $scope.criteria	= value; }
	$scope.init 		= function() {
		$scope.collection = new MACollection();
		$scope.search();
	}

	$scope.getQuery		= function() {
		let query = {};
		query.name     = $scope.name;
		query.order    = $scope.order;
		query.criteria = $scope.criteria;
		return query;
	}

	$scope.search = function() {
		$scope.collection = new MACollection();
		$resource('/process/material/json').get($scope.getQuery()).$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAMaterial.fromJSON(e)});
				$scope.collection.load(data);
				console.log($scope.collection);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.more = function() {
		$resource($scope.collection.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAMaterial.fromJSON(e)});
				$scope.collection.load(data);
				console.log($scope.collection);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.addMaterial = function() {
		let material = new MAMaterial();
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/material-form.html',
			controller: '_MaterialModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {material: material,}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
				$window.location.href = material.links.getHref();
			},
			function(err) {}
		);
	}
	$scope.editMaterial = function(material) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/material-form.html',
			controller: '_MaterialModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {material: material,}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
	$scope.deleteMaterial = function(material) {
		var war = $scope._addWarning("Deleting...");
		$resource(material.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		$scope.collection.removeElement(material);
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
});
