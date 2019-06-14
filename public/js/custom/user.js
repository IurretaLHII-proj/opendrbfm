App.controller('_UserDetailCtrl', function($scope, $resource, $location) {

	$scope.init = function(user) {
		$scope.user = new MAUser(user);
		$scope.collection = new MACollection();
		$resource($scope.user.links.getHref('actions')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAAction.fromJSON(e)});
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
				data._embedded.items = data._embedded.items.map(e => {return MAAction.fromJSON(e)});
				$scope.collection.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}
});
