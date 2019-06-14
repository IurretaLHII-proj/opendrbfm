App.controller('_CommentDetailCtrl', function($scope, $uibModalInstance, $resource, $filter, source) 
{
	$scope.source 	= source;
	$scope.users   	= [{id:null, name:" --Select suscriber-- "}, {id:11111,name:"epa"}];
	commentValues 	= function(source) {
		source.values = {suscribers: [source.user.id]};
		source.errors = {};
		if (source instanceof MAComment || source instanceof EMAComment) {
			source.suscribers.forEach(s => {
				if (source.values.suscribers.indexOf(s.id) == -1) {
					source.values.suscribers.push(s.id);
				}
			});
		}
	}

	$scope.init  	= function() {
		$resource('/user/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.users.push(new MAUser(item));
				});
				this.commentValues(source);
				$scope.replies($scope.source);
			},
			function (err) {}
		);
	}

	$scope.filterAdded = function(source) {
		return function(item) {return (!item.id || source.values.suscribers.indexOf(item.id) == -1)}
	}

	$scope.getSuscriber = function(id) {
		return $scope.users.find(e => {return e.id == id});
	}

	$scope.addSuscriber = function(source) {
		source.values.suscribers.push(null);
	}

	$scope.rmSuscriber = function(source, suscriber) {
		source.values.suscribers.splice(source.values.suscribers.indexOf(suscriber), 1);
	}

	$scope.highest = null;
	$scope.loadParent = function(comment) {
		comment.parentLoading = true;
		$resource(comment.parent.links.getHref()).get().$promise.then(
			function(data) {
				$scope.highest = new EMAComment(data);
				if (comment != $scope.source) {
					$scope.highest.addComment(comment);
			    }	
				comment.parentLoading = false;
				comment.parentLoaded  = true;
			},
			function(err) {}
		);
	}

	$scope.reply = function(source) {
		var war = $scope._addWarning("Replying...");
		var uri = source instanceof MAComment || source instanceof EMAComment ?
			source.links.getHref('reply') : source.links.getHref('comment');
		return $resource(uri).save(source.values).$promise.then(
			function(res) {
				$scope._closeWarning(war);
				let cmm = new MAComment(res);
				source.addComment(cmm);
				commentValues(cmm);
				commentValues(source);
				console.log(source);
			},		
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
				source.errors = err.data.errors;
			}
		);	
	};

	$scope.replies = function(source) {
		if (source.commentsLoaded) {
			source.comments = new MACollection();
			source.commentsLoaded  = false;
		}
		else {
			source.commentsLoading = true;
			$resource(source.links.getHref('comments')).get({limit:5}).$promise.then(
				function(data) {
					data._embedded.items = data._embedded.items.map(raw => {
						let cmm = new MAComment(raw);
						//FIXME: collection load and add methods
						if (source instanceof MAComment || source instanceof EMAComment) {
							cmm.parent = source;
						}
						else {
							cmm.source = source;
						}
						this.commentValues(cmm);
						return cmm;
					}); 
					source.commentsLoaded  = true;
					source.commentsLoading = false;
					source.comments.load(data);
					console.log(source);
				},		
				function(err) {
					console.log(err);
				}
			);	
		}
	};

	$scope.more = function(source) {
		source.commentsLoading = true;
		$resource(source.comments.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(raw => {
					let cmm = new MAComment(raw);
					//FIXME: collection load and add methods
					if (source instanceof MAComment || source instanceof EMAComment) {
						cmm.parent = source;
					}
					else {
						cmm.source = source;
					}
					this.commentValues(cmm);
					return cmm;
				}); 
				source.commentsLoaded  = true;
				source.commentsLoading = false;
				source.comments.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.rmComment = function(comment) {
		console.log(comment);
		var war = $scope._addWarning("Deleting...");
		$resource(comment.links.getHref('delete')).delete().$promise.then(
			function (data) {
				if (comment.parent) {
					comment.parent.removeComment(comment);
				}
				else { 
					comment.source.removeComment(comment);
				}
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

	$scope.formatBody = function(comment) {
		var body = comment.body;
		body 	 = body.replace(/^(<([^>]+)>)/ig,"");	
		body 	 = body.replace(/(<([^>]+)>$)/ig,"");	

		tagA = "<a href='"+comment.user.links.getHref()+"' class=''>"+comment.user.name+"</a>";
		tagS = "<small class='text-muted'>"+$filter('date')(comment.created,"MMM d, H:mm")+"</small>";

		return tagA+'. '+body; //+". "+tagS;
	}

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('CommentsCtrl', function($scope, $uibModalInstance, $resource, source) 
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
				comment.errors = err.data.errors;
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
