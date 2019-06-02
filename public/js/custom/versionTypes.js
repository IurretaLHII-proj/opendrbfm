App.controller('_VersionTypeCollectionCtrl', function($scope, $resource, $uibModal, $window) {

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
		$resource('/process/version/type/json').get($scope.getQuery()).$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAVersionType.fromJSON(e)});
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
				data._embedded.items = data._embedded.items.map(e => {return MAVersionType.fromJSON(e)});
				$scope.collection.load(data);
				console.log($scope.collection);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.addVersionType = function() {
		let versionType = new MAVersionType();
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/version-type-form.html',
			controller: '_VersionTypeModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {type: versionType,}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
				$window.location.href = versionType.links.getHref();
			},
			function(err) {}
		);
	}
	$scope.editVersionType = function(versionType) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/version-type-form.html',
			controller: '_VersionTypeModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {type: versionType,}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
	$scope.deleteVersionType = function(versionType) {
		var war = $scope._addWarning("Deleting...");
		$resource(versionType.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		$scope.collection.removeElement(versionType);
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
