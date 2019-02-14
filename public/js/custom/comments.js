App.controller('CommentCtrl', function($scope, $uibModalInstance, $api, item) 
{
	item.comments = [];
	$scope.item   = item;
	$scope.values = {};
	$scope.save = function() {
		//$uibModalInstance.close(item);	
		var war = $scope._addWarning("Saving...");
		$api.hint.comment(item, $scope.values).then(
			function(comment) {
				$scope.values = {};
				$scope._closeWarning(war);
				item.comments.push(comment);
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
				$scope._closeWarning(war);
				comment.addChild(child);
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.errors = err.data.errors;
			}
		);	
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};

	$scope.replies = function(comment) {
		var war = $scope._addWarning("Loading...");
		comment.loaded = true;
		$api.comment.replies(comment).then(
			function(data) {
				comment.addChildren(data);
				$scope._closeWarning(war);
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
			}
		);	
	};

	$scope.init = function() {
		$api.hint.comments(item).then(
			function(data) {
				item.comments = item.comments.concat(data);
				console.log(item);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	};
});

App.service('$api', ['$resource', function($res) {
	return {
		hint: {
			comment: function(item, values) {
				return $res(item._links.comment.href).save(values).$promise.then(
					function(data) { return new MAComment(data); }, function(err) { throw err; }
				);
			},
			comments: function(item) {
				return $res(item._links.comments.href).get().$promise.then(
					function(data) { 
						return data._embedded.items.map(function(values) {
							return new MAComment(values);
						}); 
					}, function(err) { throw err; }
				);
			},
		},
		comment: {
			reply: function(item, values) {
				console.log(item, values);
				return $res(item.links.getHref('reply')).save(values).$promise.then(
					function(data) { return new MAComment(data); }, function(err) { throw err; }
				);
			},
			replies: function(item) {
				return $res(item.links.getHref('children')).get().$promise.then(
					function(data) { 
						return data._embedded.items.map(function(values) {
							return new MAComment(values);
						}); 
					}, function(err) { throw err; }
				);
			},
		}
	};
}]);
