var App = angular.module('myApp', [
		'ngResource',
		'ngSanitize',
		'ngAnimate',
		'textAngular',
		'ui.bootstrap',
		]);

App.config(function($rootScopeProvider) {
    $rootScopeProvider.digestTtl(20); //FIXME bad for performance: some number bigger then 10
})

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

App.directive('includeReplace', function () {
    return {
        require: 'ngInclude',
		restrict: 'A', /* optional */
        link: function (scope, el, attrs) {
            el.replaceWith(el.children());
        }
    };
});

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

App.service('operationTypeApi', ['$resource', function($res) {
	return {
		getCollection: function() {
			return $res('/process/op/type/json').get().$promise.then(
				function(data) {
					return data;
				},
				function(err) {
				}
			);
		}
	}
}]);
App.service('operationApi', ['$resource', function($res) {
	return {
		getCollection: function() {
			return $res('/process/op/json').get().$promise.then(
				function(data) {
					return data;
				},
				function(err) {
				}
			);
		},
		getHints: function(op) {
			return $res(op._embedded._links.hints.href).get().$promise.then(
				function(data) {
					return data;
				},
				function(err) {
				}
			);
		}
	}
}]);


App.controller('MainCtrl', function($scope, $timeout, operationApi) {

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

	$scope.badgeClass = function(number) {
		switch (true) {
			case number > 6: return 'danger';
			case number >= 4: return 'warning';
			case number >= 2: return 'success';
			default: return 'secondary';
		}
	};

	//FIXME
	$scope.defaultOp   = {id:null, name:'--- Select one ---'};
	$scope.defaultHint = {id:null, name:'--- Select one ---'};
	var _createOption  = {id:-1,   name:'--- Create new ---'};
	operationApi.getCollection().then(function(data) {
		$scope.operations = data._embedded.items;
	});
	$scope.getOperations = function(model, currents) {
		var options = [];
		angular.forEach($scope.operations, function(op, key) {
			if (model.id == op.id || currents.map(function(e) {return e.id}).indexOf(op.id) == -1) {
				options.push(op);	
			}
		});
		options.unshift($scope.defaultOp);
		return options;
	};

});

App.controller('OperationModalCtrl', function($scope, $uibModalInstance, $resource, type, op) {
	$scope.type = type;
	$scope.values = {
		text: "",
		children : [
			op, 
			{},
			//$scope.defaultOp,
		]
	};

	$scope.save = function() {
		var raw = {operation: angular.copy($scope.values)};
		angular.forEach(raw.operation.children, function(child) {
			child.type = type.id;
			if (raw.operation.text.length) raw.operation.text += "+";
			raw.operation.text += child.text;
		});
		console.log(raw, $scope.values);
		$resource(type._embedded._links.operation.href).save(raw).$promise.then(
			function(data) {
				$uibModalInstance.close(data);	
				//$scope.operations.push(data);
			},
			function(err) {
				$scope.errors = err.data.errors;
			},
		);
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('HintTypeModalCtrl', function($scope, $uibModalInstance, $resource, op) {
	$scope.operation = op;
	$scope.values = {};

	$scope.save = function() {
		$resource(op._links.hint.href).save($scope.values).$promise.then(
			function(data) {
				$uibModalInstance.close(data);	
				op.hints.push(data);
			},
			function(err) {
				$scope.errors = err.data.errors;
			},
		);
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('OperationsCtrl', function($scope, $resource, $uibModal, operationApi) {
	$scope.collection = null;

	$scope.init = function(collection) {
		$scope.collection = collection._embedded.items;
		$scope.values = angular.copy($scope.collection);
		angular.forEach($scope.values, function(type, index) {
			$scope.values[index] = type;
			type.operations = [];
			angular.forEach(type._embedded.operations, function(op, i) {
				type.operations[i] = op;
				op._embedded = $scope.collection[index]._embedded.operations[i];
			});
			delete type._embedded.operations;
			delete type._embedded;
			/*if (!type.operations.length) $scope.addOp(type);*/
			type._embedded = $scope.collection[index];
		});
		console.log(collection, $scope.values);
	};

	//UPDATE TYPE 
	$scope.submitType = function(type, i) {
		var raw = angular.copy(type);
		$resource(type._embedded._links.edit.href).save(raw).$promise.then(
			function (data) {
				type.errors = null;
				type.editor = false;
				// ?¿ type._embedded._embedded.operations[i] = data;
				type._embedded = data;
				$scope.addSuccess("Saved succesfully")
				console.log($scope.collection, $scope.values, $scope.messages);
			},
			function (err) {
				$scope.addError(err.data.title);
				type.errors = err.data.errors;
			}	
		);
	}
	//DELETE TYPE 
	$scope.deleteType = function(type, i) {
		$resource(type._embedded._links.delete.href).delete().$promise.then(
			function (data) {
        		$scope.values.splice(i, 1);
				$scope.addSuccess("Succesfully deleted");
				console.log($scope.collection, $scope.values, $scope.messages);
			},
			function (err) {
				$scope.addError(err.data.title);
				type.errors = err.data.errors;
			}	
		);
	}

	$scope.closeType = function(type, i) {
		type.editor = false;
		if (!type._embedded) {
        	$scope.values.splice(i, 1);
		}
	}

	//ADD or UPDATE OPERATIONS 
	$scope.submitOp = function(type, op, index) {
		var resource;
		if (op._embedded) {
			resource = $resource(op._embedded._links.edit.href);
		}
		else {
			resource = $resource(type._embedded._links.operation.href);
		}
		var raw = {operation: angular.copy(op)};
		resource.save(raw).$promise.then(
			function (data) {
				op.errors = null;
				op.editor = false;
				type._embedded._embedded.operations[index] = data;
				op._embedded = data;
				$scope.addSuccess("Saved succesfully");
				console.log($scope.collection, $scope.values, $scope.messages);
			},
			function (err) {
				$scope.addError(err.data.title);
				op.errors = err.data.errors;
			}	
		);
	}
	//DELETE OPERATION
	$scope.deleteOp = function(type, op, index) {
		$resource(op._embedded._links.delete.href).delete().$promise.then(
			function (data) {
        		type.operations.splice(index, 1);
				type._embedded._embedded.operations.splice(index, 1);
				if (!type.operations.length) $scope.addOp(type);
				$scope.addSuccess("Succesfully deleted");
				console.log($scope.collection, $scope.values, $scope.messages);
			},
			function (err) {
				$scope.addError(err.data.title);
				hint.errors = err.data.errors;
			}	
		);
	}

	$scope.loadOpHints = function(op) {
		operationApi.getHints(op).then(function(data) {
			console.log(op);
			op.hints = data._embedded.items;
		});
	}

	$scope.closeOp = function(type, op, i) {
		op.editor = false;
		if (!op._embedded) {
        	type.operations.splice(i, 1);
		}
	}

	$scope.addOp = function(type) {
		var op = {
			parents:[{}],
		};
		op.editor = true;
		type.operations.push(op);
	}

	//$scope.defaultOpParent = function(op, i) {}
	$scope.operationDialog = function(type, op) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : 'operationModalContent.html',
			controller: 'OperationModalCtrl',	
			//scope: $scope,
			size: 'lg',
			resolve: {
				type: type,
				op : op,
			}
		});

		modal.result.then(
			function(res) {
			},
			function() {
			}
		);
	} 
});

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

App.controller('DetailCtrl', function($scope, $resource, $uibModal, $timeout, operationApi) {

	$scope.errors = {};
	
	var _prepareOperation = function(op) {
		_op = angular.copy(op);
		_op._embedded = op;
		return _op;
	}
	
	var _prepareHint = function(hint) {
		_hint = angular.copy(hint);
		_hint._embedded = hint;
		_hint.operation = hint._embedded.operation;
		_hint.type 		= hint._embedded.type;
		_hint.parents = [];
		_hint.reasons = [];
		_hint.suggestions = [];
		_hint.influences = [];
		angular.forEach (hint._embedded.parents, function(prt, p) {
			_hint.parents[p] = prt;
		});
		angular.forEach (hint._embedded.reasons, function(note, n) {
			_hint.reasons[n] = note;
		});
		angular.forEach (hint._embedded.suggestions, function(note, n) {
			_hint.suggestions[n] = note;
		});
		angular.forEach (hint._embedded.influences, function(note, n) {
			_hint.influences[n] = note;
		});
		return _hint;
	}

	var _prepareStage = function(stage) {
		_stage = angular.copy(stage);
		_stage._embedded = stage;

		_stage.operations = [];
		angular.forEach(stage._embedded.operations, function(op, i) {
			_stage.operations[i] = _prepareOperation(op);
		});
		if (!_stage.operations.length) _stage.operations.push({});

		_stage.hints = [];
		angular.forEach(stage._embedded.hints, function(hint, i) {
			_stage.hints[i] = _prepareHint(hint);
		});
		if (!_stage.hints.length) $scope.addHint(_stage);

		_stage.images = [];
		angular.forEach(stage._embedded.images, function(image, i) {
			_stage.images[i] = image;
		});	
		return _stage;
	}


	//$scope.current = function(id, block) {
	//	if ($scope.blockCurrent) return;
	//	angular.forEach($scope.values.stages, function(stage) {
	//		if (stage.id == id){
	//		   	$scope.values.current = stage;
	//		}
	//	});
	//	$scope.blockCurrent = block;
	//}

	var _newStage = function(parentStage) {
		var _stage = {
			version: 1,
			level: 1,
			hints:[],
			images:[],
			operations:[$scope.defaultOp],
			children:[],
			editor: true,
			_links: {
				image: {href: '/process/stage/image/json'}
			}
		};
		if (parentStage) {
			parentStage.children.push(_stage);
			_stage.version = parentStage.version;
			_stage.level = parentStage.level + 1;
		}
		console.log(_stage);
		return _stage;
	}

	var _initVersion = function(version, items) {
		version.children = [];
		while(items.length) {
			item = _prepareStage(items.shift(items));
			item.parent = version;
			version.children.push(item);
			_initVersion(item, items);
		}
	}

	$scope.init = function(item, values) {
		$scope.values = {versions: []};
		if (item) {
			$scope.entity = item;
			if (item._embedded &&
				item._embedded.versions && 
				item._embedded.versions.length) {

				angular.forEach(item._embedded.versions, function(version, index) {
					_stage = _prepareStage(version);
					$scope.values.versions[index] = _stage;
					if (!$scope.values.current) {
						$scope.values.current = _stage;
					}
				});
			}
			else {
				$scope.addVersion();
			}
		}

		if (values) {
			angular.merge($scope.values, values);
		}

		operationApi.getCollection().then(function(data) {
			$scope.operations = data._embedded.items;
		});

		//console.log($scope.entity, $scope.values);
	}

	$scope.initVersion = function(data, id) {
		angular.forEach($scope.values.versions, function(version) {
			if (!id || version.id == id) {
				_initVersion(version, data._embedded.items);	
				$scope.version = version;
				$scope.values.current = version;
				id = version.id;
			}
		});
	}

	$scope.loadVersion = function(version) {
		if (!version.children) {
			$resource(version._links.children.href).get().$promise.then(
				function (data) {
					_initVersion(version, data._embedded.items);
					console.log(version);
				},
				function (err) {
					console.log(err);
				}	
			);
		}
		$scope.version = version;
		$scope.values.current = version;
	}

	$scope.addVersion = function() {
		_stage = _newStage();
		if ($scope.version) {
			_stage.version = $scope.version.version+1;
		}
		_stage.level = 1;
		$scope.values.versions.push(_stage);
		$scope.version = _stage;
		$scope.values.current = _stage;
	}

    $scope.addStage = function(parentStage) {
		var stage = _newStage(parentStage); 
		if (parentStage) stage.parent = parentStage;
        $scope.values.current = stage;
		console.log(stage);
    }

	$scope.addHint = function(stage) {
		var hint = {
			type:$scope.defaultOp,
			operation:$scope.defaultOp,
			parents:[], 
			reasons:[],
			suggestions:[],
			influences:[],
		};
		if (stage.operations.length) {
			hint.operation = stage.operations[0];
		}
		hint.editor = true;
		stage.hints.push(hint);
	}

	$scope.openStageForm = function(stage) {
		stage.editor = true;
	}

	$scope.closeStageForm = function(stage) {
		stage.editor = false;
		if (!stage._embedded) {
			if (stage.parent) {
        		stage.parent.children = [];
				$scope.values.current = stage.parent;
			}
			else {
        		$scope.values.versions.splice($scope.values.versions.length-1, 1);
				$scope.loadVersion($scope.values.versions[$scope.values.versions.length-1]);
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

	//ADD PROCESS
	$scope.submitProcess = function() {
		var raw = angular.copy($scope.values);
		$resource($scope.entity._links.edit.href).save(raw).$promise.then(
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
		if (raw.parent) raw.parent = raw.parent._embedded.id;
		delete raw._embedded;
		delete raw.children;
		console.log(stage, raw);
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
				console.log(err);
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
		raw.type = raw.type.id;
		resource.save(raw).$promise.then(
			function (data) {
				hint.errors = null;
				hint.editor = false;
				stage._embedded._embedded.hints[index] = data;
				hint._embedded = data;
				$scope.addSuccess("Saved succesfully");
				console.log(hint, $scope.values, $scope.messages);
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

	$scope.getOperations = function(model, currents) {
		var options = [];
		angular.forEach($scope.operations, function(op, key) {
			if (model.id == op.id || currents.map(function(e) {return e.id}).indexOf(op.id) == -1) {
				options.push(op);	
			}
		});
		options.unshift($scope.defaultOp);
		return options;
	}

	$scope.getPrevs = function(stage, model, parents) {
		var options = [];
		if (stage.parent) {
			var current = stage.parent;
			while (current) {
				angular.forEach(current.hints, function(h) {
					if (h._embedded && (h.id == model.id || parents.map(function(e) {return e.id}).indexOf(h.id) == -1)) {
						options.push(h._embedded);
					}
				});
				current = current.parent;
			}
		}
		options.unshift($scope.defaultHint);
		return options;
	}

	$scope.loadHintTypes = function(hint) {
		if (hint.operation.id > 0) {
			hint.operation.hints = [];
			operationApi.getHints(hint.operation).then(function(data) {
				hint.operation.hints = data._embedded.items;
			});
		}
		//FIX when change selector
		//if (!hint.type) hint.type = $scope.defaultOp; 
	};

	$scope.getHintOptions = function(op) {
		var options = [];
		if (op.hints) {
			options = angular.copy(op.hints);
		}
		options.push(_createOption);
		options.unshift($scope.defaultOp);
		return options;
	}

	$scope.operationDialog = function(hint) {

	 	if (hint.type && hint.type.id < 0) {	
			var modal = $uibModal.open({
				animation: true,
				templateUrl : 'hintTypeModalContent.html',
				controller: 'HintTypeModalCtrl',	
				size: 'lg',
				resolve: {
					op : function() { 
						return hint.operation; 
					},
				}
			});

			modal.result.then(
				function(res) {
					hint.type = res;
				},
				function() {
					hint.type = $scope.defaultOp;
				}
			);
		}
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

	/*$scope.getStages = function(version) {
		var stage = version;
		var children = [stage];
		while (stage.children.length > 0) {
			var child = stage.children[0];
			children.push(child);
			stage = child;
		}
		return children;
	}

	$scope.lastStage = function(version) {
		var children = $scope.getStages(version);
		var child 	 = children.splice(children.length-1, 1);
		return child;
	}

    $scope._init = function(item, values) {
		if (item) {
			$scope.entity = item;
			$scope.values = angular.copy(item);
    		$scope.values.stages = [];
			if ($scope.values._embedded) {

				if ($scope.values._embedded.stages && $scope.values._embedded.stages.length) {

					angular.forEach($scope.values._embedded.stages, function(stage, index) {
						$scope.values.stages[index] = stage;
						stage.operations = [];
						angular.forEach(stage._embedded.operations, function(op, i) {
							stage.operations[i] = op;
							//Save an reference of original entity to sync them
							op._embedded = $scope.entity._embedded.stages[index]._embedded.operations[i];
						});	
						if (!stage.operations.length) stage.operations.push({});
						delete stage._embedded.operations;
						stage.hints = [];
						angular.forEach(stage._embedded.hints, function(hint, i) {
							stage.hints[i] = hint;
							hint.parents = [];
							hint.reasons = [];
							hint.suggestions = [];
							hint.influences = [];
							angular.forEach (hint._embedded.parents, function(prt, p) {
								hint.parents[p] = prt;
							});
							angular.forEach (hint._embedded.reasons, function(note, n) {
								hint.reasons[n] = note;
							});
							angular.forEach (hint._embedded.suggestions, function(note, n) {
								hint.suggestions[n] = note;
							});
							angular.forEach (hint._embedded.influences, function(note, n) {
								hint.influences[n] = note;
							});
							delete hint._embedded;
							//Save an reference of original entity to sync them
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

		operationApi.getCollection().then(function(data) {
			$scope.operations = data._embedded.items;
		});
		console.log($scope.entity, $scope.values);
    }*/
});
