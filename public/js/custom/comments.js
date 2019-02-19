App.controller('CommentCtrl', function($scope, $uibModalInstance, $api, item) 
{
	item.comments = [];
	if (typeof item != MANote) {
		$scope.item   = new MAHint();
		$scope.item.name  = item.name;
		$scope.item.links = new MALinks(item._links);
	}
	$scope.values = {};
	$scope.save = function() {
		var war = $scope._addWarning("Saving...");
		$api.hint.comment($scope.item, $scope.values).then(
			function(comment) {
				$scope.values = {};
				$scope._closeWarning(war);
				$scope.item.comments.items.push(comment);
				$scope.item.addComments(comment);
				//$uibModalInstance.close(item);	
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.errors = err.data.errors;
			}
		);	
	};

	$scope.reply = function(comment) {
		var war = $scope._addWarning("Saving...");
		$api.comment.reply(comment, comment.values).then(
			function(child) {
				comment.values = {};
				comment.commentCount++;
				comment.addChild(child);
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
		$api.comment.replies(comment).then(
			function(data) {
				data._embedded.items = data._embedded.items.map(function(values) {
					return new MAComment(values);
				}); 
				comment.children.load(data);
				$scope._closeWarning(war);
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
			}
		);	
	};

	$scope.more = function(coll) {
		$api.collection.more(coll).then(
			function(data) {
				data._embedded.items = data._embedded.items.map(function(values) {
					return new MAComment(values);
				}); 
				coll.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.init = function() {
		$api.hint.comments($scope.item).then(
			function(data) {
				data._embedded.items = data._embedded.items.map(function(values) {
					return new MAComment(values);
				}); 
				$scope.item.comments.load(data);
				console.log($scope.item);
			},		
			function(err) {
				$scope.errors = err;
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
