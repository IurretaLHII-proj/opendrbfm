App.controller('_PlantCollectionCtrl', function($scope, $resource, $uibModal, $window) {

	$scope.name			= "";
	$scope.order		= "name";
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
		$resource('/process/plant/json').get($scope.getQuery()).$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAPlant.fromJSON(e)});
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
				data._embedded.items = data._embedded.items.map(e => {return MAPlant.fromJSON(e)});
				$scope.collection.load(data);
				console.log($scope.collection);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.addPlant = function() {
		let plant = new MAPlant();
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/plant-form.html',
			controller: '_PlantModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {plant: plant,}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
				$window.location.href = plant.links.getHref();
			},
			function(err) {}
		);
	}
	$scope.editPlant = function(plant) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/plant-form.html',
			controller: '_PlantModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {plant: plant,}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
	$scope.deletePlant = function(plant) {
		var war = $scope._addWarning("Deleting...");
		$resource(plant.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		$scope.collection.removeElement(plant);
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
