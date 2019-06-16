App.controller('_UserDetailCtrl', function($scope, $resource, $location) {

	$scope.readed = false;

	$scope.init = function(user) {
		$scope.user = new MAUser(user);
		$scope.collection = new MACollection();
		$resource($scope.user.links.getHref('actions')).get({limit:10}).$promise.then(
			function(actions) {
				actions._embedded.items = actions._embedded.items.map(e => {return MAAction.fromJSON(e)});
				$scope.notificationQuery();
				$scope.collection.load(actions);
				console.log($scope.collection, $scope.notifications);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.notificationQuery = function() {
		$scope.notifications = new MACollection();
		$resource($scope.user.links.getHref('notifications')).get({limit:10, readed:$scope.readed ? 1 : 0}).$promise.then(
			function(notifications) {
				notifications._embedded.items = notifications._embedded.items.map(e => {return MANotification.fromJSON(e)});
				$scope.notifications.load(notifications);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.more = function(coll) {
		$resource(coll.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MANotification.fromJSON(e)});
				coll.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.asRead = function(notification) {
		var war = $scope._addWarning("Sending request..");
		$resource(notification.links.getHref('read')).get().$promise.then(
			function(data) {
				$scope.notifications.removeElement(notification);
				$scope._closeWarning(war);
				$scope.addSuccess("Marked as read succesfully");
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.delete = function(notification) {
		var war = $scope._addWarning("Sending request..");
		$resource(notification.links.getHref('delete')).get().$promise.then(
			function(data) {
				$scope.notifications.removeElement(notification);
				$scope._closeWarning(war);
				$scope.addSuccess("Deleted succesfully");
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}
});
