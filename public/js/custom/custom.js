var App = angular.module('myApp', ['ngResource', 'ngSanitize', 'ngAnimate', 'textAngular']);

if (!angular.lowercase) angular.lowercase = str => angular.isString(str) ? str.toLowerCase() : str;

App.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('{[').endSymbol(']}');
});

App.directive('compile', ['$compile', function ($compile) {
	return function(scope, element, attrs) {
          var ensureCompileRunsOnce = scope.$watch(
            function(scope) {
               // watch the 'compile' expression for changes
              return scope.$eval(attrs.compile);
            },
            function(value) {
              // when the 'compile' expression changes
              // assign it into the current DOM
              element.html(value);

              // compile the new DOM and link it to the current
              // scope.
              // NOTE: we only compile .childNodes so that
              // we don't get into infinite loop compiling ourselves
              $compile(element.contents())(scope);
                
              // Use Angular's un-watch feature to ensure compilation only happens once.
              ensureCompileRunsOnce();
            }
        );
    };
}]);

App.directive('fileChange', ['$resource', '$parse', function($res, $parse) {
    return {
        restrict : 'A',
        //scope : {index: "=fileIndex"}, 
        scope: true,
        link: function(scope, elem, attrs) {
            elem.bind('change', function(ev) {
                var onChangeHandler = scope.$eval(attrs.fileChange);
                onChangeHandler(ev, scope.$eval(attrs.fileEntity), scope.$eval(attrs.fileIndex)); 
				scope.$apply();
            });
        }
    }
}]);

App.directive('ngConfirmClick', [function() {
	return {
		link: function(scope, elem, attrs) {
			var msg    = attrs.ngConfirmClick || "Are you sure?";
			var action = attrs.confirmedClick;
			elem.bind('click', function(ev) {
				if (window.confirm(msg)){
					scope.$eval(action);
				}
			});
		}
	}
}]);

App.controller('CollectionCtrl', function($scope, $resource) {
	$scope.collection = null;

	$scope.init = function(collection) {
		$scope.collection = collection;
		console.log(collection);
	};

	$scope.more = function() {
		$resource($scope.collection._links.next.href).get().$promise.then(
			function (data) {
				angular.forEach(data._embedded.items, function(item) {
					//$scope.collection._embedded.items.unshift(item);
					$scope.collection._embedded.items.push(item);
				});
				$scope.collection._links = data._links;
				$scope.collection.page   = data.page;
			},
			function (err) {
				console.log(err);
			}	
		);
	}
});

App.controller('DetailCtrl', function($scope, $resource, $timeout) {

	$scope.errors = {};
    //$scope.values = {
	//	stages: [{images: []}],
    //};

    $scope.init = function(item, values) {
		if (item) {
			$scope.entity = item;
			$scope.values = angular.copy(item);
    		$scope.values.stages = [];
			if ($scope.values._embedded) {

				if ($scope.values._embedded.stages && $scope.values._embedded.stages.length) {

					angular.forEach($scope.values._embedded.stages, function(stage, index) {
						$scope.values.stages[index] = stage;
						stage.hints = [];
						angular.forEach(stage._embedded.hints, function(hint, i) {
							hint.created = new Date(hint.created);
							stage.hints[i] = hint;
							hint.parents = [];
							angular.forEach (hint._embedded.parents, function(prt, p) {
								hint.parents[p] = prt;
							});
							delete hint._embedded;
							//Save an reference of original entity to sync
							hint._embedded = $scope.entity._embedded.stages[index]._embedded.hints[i];
						});	
						if (!stage.hints.length) $scope.addHint(stage);
						delete stage._embedded.hints;
						stage.images = [];
						angular.forEach(stage._embedded.images, function(image, i) {
							stage.images[i] = image;
						});	
						delete stage._embedded.images;
						delete stage._embedded;
						if (!$scope.values.current) {
							$scope.values.current = stage;
						}
						stage._embedded = $scope.entity._embedded.stages[index];
					});	
					delete $scope.values._embedded.stages;
				}
				else {
					$scope.addStage();
				}
				if (!$scope.values.current.hints.length) {
					$scope.addHint($scope.values.current);
				}
				delete $scope.values._embedded;
			}
		}
		if (values) {
			angular.merge($scope.values, values);
		}
		console.log($scope.entity, $scope.values);
    }

    $scope.addStage = function(parentStage) {
		var stage = {
			hints:[],
			images:[],
			_links: {
				image: {href: '/process/stage/image/json'}
			}
		};
		if (parentStage) stage.parent = parentStage;
        $scope.values.stages.push(stage);
        $scope.values.current = stage;
        $scope.values.current.editor = true;
    }

	$scope.addHint = function(stage) {
		var hint = {parents:[]};
		hint.editor = true;
		stage.hints.push(hint);
	}

	$scope.closeStage = function(stage, i) {
		stage.editor = false;
		if (!stage._embedded) {
        	$scope.values.stages.splice(i, 1);
			if (stage == $scope.values.current) {
				$scope.values.current = $scope.values.stages[$scope.values.stages.length-1];
			}
		}
	}

	$scope.closeHint = function(stage, hint, i) {
		hint.editor = false;
		if (!hint._embedded) {
        	stage.hints.splice(i, 1);
		}
	}

    $scope.addImage = function(stage) {
        stage.images.push({});
    }

	$scope.removeImage = function(stage, i) {
        stage.images.splice(i, 1);
	}

    var imagePreview = function(data, stage, index) {
		console.log(data, stage, index);
        stage.images[index] = data;
    }

	//Update Stage image
    $scope.uploadFile = function(ev, stage, index) {
        var data = new FormData;
        var file = ev.target.files[0];
        var resource = $resource(stage._links.image.href, {}, {
            upload: {
                method: 'POST',
                transformRequest: function(data) {
                    if (data === undefined)
                      return data;

                    var fd = new FormData();
                    angular.forEach(data, function(value, key) {
                      if (value instanceof FileList) {
                        if (value.length == 1) {
                          fd.append(key, value[0]);
                        } else {
                          angular.forEach(value, function(file, index) {
                            fd.append(key + '_' + index, file);
                          });
                        }
                      } else {
                        fd.append(key, value);
                      }
                    });
                    return fd;
                },
                headers: {'Content-Type' : undefined}
            }
        });
        resource.upload({'file': file})
             .$promise
             .then(
                 function(data) {
			  		imagePreview(data, stage, index);
                 },
                 function(err) {
				 	stage.errors = err.data.errors;
                 }
             );
    }

	//ADD PROCESS
	$scope.submitProcess = function() {
		$resource($scope.entity._links.edit.href).save($scope.values).$promise.then(
			function (data) {
				$scope.entity.errors = null;
				$scope.entity.editor = false;
				$scope.entity.title = data.title;
				$scope.entity.body = data.body;
				$scope.addSuccess("Saved succesfully");
				console.log($scope.entity, $scope.values);
			},
			function (err) {
				console.log(err);
				$scope.addError(err.data.title);
				$scope.entity.errors = err.data.errors;
			}	
		);
	}

	//ADD or UPDATE STAGE
	$scope.submitStage = function(stage, index) {
		var resource;
		if (stage._embedded) {
			resource = $resource(stage._embedded._links.edit.href);
		}
		else {
			resource = $resource($scope.entity._links.stage.href);
		}
		var raw = angular.copy(stage);
		if (raw.parent) raw.parent = raw.parent.id;
		resource.save(raw).$promise.then(
			function (data) {
				stage.errors = null;
				stage.editor = false;
				$scope.entity._embedded.stages[index] = data;
				stage._embedded = data;
				if (!stage.hints.length) $scope.addHint(stage);
				$scope.addSuccess("Saved succesfully");
				console.log(index, $scope.entity, $scope.values);
			},
			function (err) {
				$scope.addError(err.data.title);
				stage.errors = err.data.errors;
			}	
		);
	}

	//ADD or UPDATE HINT 
	$scope.submitHint = function(stage, hint, index) {
		var resource;
		if (hint._embedded) {
			resource = $resource(hint._embedded._links.edit.href);
		}
		else {
			resource = $resource(stage._embedded._links.hint.href);
		}
		var raw = angular.copy(hint);
		if (raw.parents) {
			var parents = [];
			angular.forEach(raw.parents, function(item) {parents.push(item.id)});
			raw.parents = parents;
		}
		resource.save(raw).$promise.then(
			function (data) {
				hint.errors = null;
				hint.editor = false;
				stage._embedded._embedded.hints[index] = data;
				hint._embedded = data;
				$scope.addSuccess("Saved succesfully");
				console.log($scope.entity, $scope.values, $scope.messages);
			},
			function (err) {
				$scope.addError(err.data.title);
				hint.errors = err.data.errors;
			}	
		);
	}
	//DELETE HINT 
	$scope.deleteHint = function(stage, hint, index) {
		$resource(hint._embedded._links.delete.href).delete().$promise.then(
			function (data) {
        		stage.hints.splice(index, 1);
				stage._embedded._embedded.hints.splice(index, 1);
				if (!stage.hints.length) $scope.addHint(stage);
				$scope.addSuccess("Succesfully deleted");
				console.log($scope.entity, $scope.values, $scope.messages);
			},
			function (err) {
				$scope.addError(err.data.title);
				hint.errors = err.data.errors;
			}	
		);
	}

	$scope.closeError = function(err) {
		var i = $scope.messages.errors.indexOf(err);
		$scope.messages.errors.splice(i, 1);
	}
	$scope.addError = function(err) {
		$scope.messages.errors.push(err);
		$timeout(function() {
			$scope.closeError(err);
		}
		, 1500);
	}
	$scope.closeSuccess = function(succ) {
		var i = $scope.messages.success.indexOf(succ);
		$scope.messages.success.splice(i, 1);
	}
	$scope.addSuccess = function(succ) {
		$scope.messages.success.push(succ);
		$timeout(function() {
			$scope.closeSuccess(succ);
		}
		, 1500);
	}

	$scope.messages = {
		success: [],
		errors: [],
		warnings: [],
	};
});
