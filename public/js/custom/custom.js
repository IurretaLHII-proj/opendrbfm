var App = angular.module('myApp', ['ngResource', 'ngSanitize', 'textAngular']);

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

App.controller('DetailCtrl', function($scope, $resource) {

	$scope.errors = {};
    $scope.values = {
		stages: [{images: []}],
    };

    $scope.init = function(item, values) {
		if (item) {
			$scope.entity = item;
			$scope.values = angular.copy(item);
    		$scope.values.stages = [];
			if ($scope.values._embedded) {
				if ($scope.values._embedded.stages && $scope.values._embedded.stages.length) {
					angular.forEach($scope.values._embedded.stages, function(stage, index) {
						$scope.values.stages[index] = stage;
						if (stage._embedded && stage._embedded.images) {
							stage.images = [];
							angular.forEach(stage._embedded.images, function(image, index) {
								stage.images[index] = image;
							});	
						}
						if (!$scope.values.current) {
							$scope.values.current = stage;
						}
					});	
				}
				else {
					$scope.addStage();
				}
				delete $scope.values._embedded;
			}
		}
		if (values) {
			angular.merge($scope.values, values);
		}
		console.log($scope.entity, $scope.values);
    }

    $scope.addStage = function() {
		var stage = {
			images:[],
			_links: {
				image: {href: '/process/stage/image/json'}
			}
		};
        $scope.values.stages.push(stage);
        $scope.values.current = stage;
        $scope.values.current.editor = true;
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
                     console.log(err);
                 }
             );
    }

	//Add or edit Stage
	$scope.submitStage = function(stage, index) {
		var resource;
		if (stage.id) {
			resource = $resource(stage._links.edit.href);
		}
		else {
			resource = $resource($scope.entity._links.stage.href);
		}
		resource.save(stage).$promise.then(
			function (data) {
				stage.editor = false;
				console.log($scope.entity);
			},
			function (err) {
				alert(err.data.title);
				stage.errors = err;
			}	
		);
	}
});
