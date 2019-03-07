App.controller('CommentCtrl', function($scope, $uibModalInstance, $resource, source) 
{
	$scope.source  = source;
	$scope.values  = {};

	$scope.init = function() {
		if (!source.comments.isLoaded()) {
			$resource(source.links.getHref('comments')).get().$promise.then(
				function(data) {
					data._embedded.items = data._embedded.items.map(raw => {return new MAComment(raw);}); 
					source.comments.load(data);
				},		
				function(err) {
					$scope.errors = err;
				}
			);	
		}
	};

	$scope.more = function(coll) {
		$resource(coll.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(raw => {return new MAComment(raw);}); 
				coll.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.save = function() {
		var war = $scope._addWarning("Saving...");
		return $resource(source.links.getHref('comment')).save($scope.values).$promise.then(
			function(res) {
				$scope.values = {};
				$scope._closeWarning(war);
				source.addComment(new MAComment(res));
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.errors = err.data.errors;
			}
		);	
	};

	$scope.reply = function(comment) {
		console.log(comment);
		var war = $scope._addWarning("Saving...");
		$resource(comment.links.getHref('reply')).save(comment.values).$promise.then(
			function(res) {
				comment.values = {};
				comment.commentCount++;
				comment.addChild(new MAComment(res));
				$scope._closeWarning(war);
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.errors = err.data.errors;
			}
		);	
	};

	$scope.replies = function(comment) {
		var war = $scope._addWarning("Loading...");
		$resource(comment.links.getHref('children')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(raw => {return new MAComment(raw);}); 
				comment.children.load(data);
				$scope._closeWarning(war);
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
			}
		);	
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.service('$api', ['$resource', function($res) {
	return {
		hint: {
			comment: function(item, values) {
				return $res(item.links.getHref('comment')).save(values).$promise.then(
					function(data) { return new MAComment(data); }, function(err) { throw err; }
				);
			},
			comments: function(item) {
				return $res(item.links.getHref('comments')).get().$promise.then(
					function(data) { return data}, function(err) { throw err; }
				);
			},
		},
		comment: {
			reply: function(item, values) {
				console.log(item);
				return $res(item.links.getHref('reply')).save(values).$promise.then(
					function(data) { return new MAComment(data); }, function(err) { throw err; }
				);
			},
			replies: function(item) {
				return $res(item.links.getHref('children')).get().$promise.then(
					function(data) { return data}, function(err) { throw err; }
				);
			},
		},
		collection: {
			more: function(coll) {
				return $res(coll.links.getHref('next')).get().$promise.then(
					function(data) { return data}, function(err) { throw err; }
				);
			}
		},
	};
}]);
