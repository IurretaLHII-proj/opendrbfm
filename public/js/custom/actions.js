App.controller('_ActionCollectionCtrl', function($scope, $resource, $location) {

	$scope.init = function() {
		$scope.collection = new MACollection();
		$resource('/action/json').get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAAction.fromJSON(e)});
				$scope.collection.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.more = function() {
		$resource($scope.collection.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAAction.fromJSON(e)});
				$scope.collection.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}
});
