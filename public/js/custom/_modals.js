App.controller('_HintTypeModalCtrl', function($scope, $uibModalInstance, $resource, op, type) {
	$scope.operation = op;
	$scope.values = type;
	$scope.errors = {};
	$scope.save = function() {
		$resource(type._embedded ? type._embedded._links.edit.href : op._embedded._links.hint.href)
			.save($scope.values).$promise.then(
				function(data) {
					type._embedded = data;
					$uibModalInstance.close(data);	
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

App.controller('_HintModalCtrl', function($scope, $uibModal, $uibModalInstance, $resource, api, stage, hint)
{
	console.log(stage,hint);
	$scope.errors = {};
	$scope.stage  = stage;
	$scope.values = hint;
	$scope.save = function() {
		var raw  = angular.copy($scope.values);
		raw.type = raw.type.id;
		var uri  = hint._embedded ? hint._embedded._links.edit.href : stage._embedded._links.hint.href;
		$resource(uri).save(raw).$promise.then(
			function(data) {
				hint._embedded = data;
				$uibModalInstance.close(data);	
				return data;
			},
			function(err) {
				$scope.errors = err.data.errors;
				return err;
			},
		);
	};
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
	$scope.operationOptions = [];
	$scope.operationTypes = function() {
		api.operation.getHints(hint.operation).then(function(data) {
			$scope.operationOptions = data._embedded.items;
			$scope.operationOptions.push(_defaultOption);
			$scope.operationOptions.push(_createOption);
		});
	}
	$scope.getPrevs = function(model) {
		var options = [];
		var current = stage;
		while (current.parent) {
			current = current.parent;
			//angular.forEach(current._embedded._embedded.hints, function(h) {
			angular.forEach(current.hints, function(h) {
				if (model.id == h.id || hint.parents.map(function(e) {return e.id}).indexOf(h.id) == -1) {
					options.push(h._embedded);
				}
			});
		}
		options.unshift(_defaultOption);
		return options;
	};
	$scope.addPrev = function(){
		$scope.values.parents.push(_defaultOption);
	}

	$scope.createHint = function() {
		var _type = {};
	 	if ($scope.values.type && $scope.values.type.id < 0) {	
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
				controller: '_HintTypeModalCtrl',	
				size: 'lg',
				resolve: {op : $scope.values.operation, type: _type},
			});

			modal.result.then(
				function(res) {
					$scope.operationOptions.push(res);
					hint.type = res;
				},
			);
		}
	}
});

App.controller('_RenderModalCtrl', function($scope, $uibModalInstance, $resource, $filter, hint) {
	//console.log(hint);
	$scope.stateOptions = _stateOptions;
	$scope.errors = {};
	$scope.values = hint; 
	$scope.save = function() {
		var raw = angular.copy($scope.values);
		raw.when = raw.when ? $filter('date')(new Date(raw.when), 'yyyy-MM-dd') : null;
		$resource(hint._embedded._links.render.href).save(raw).$promise.then(
			function(data) {
				hint._embedded = data;
				$uibModalInstance.close(data);	
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

App.controller('_StageModalCtrl', function($scope, $uibModalInstance, $resource, api, process, stage) {
	console.log(process, stage);
	api.operation.getAll().then(function(data) {
		$scope.operations = data;
	});
	api.material.getAll().then(function(data) {
		$scope.materials = data;
	});
	$scope.errors = {};
	$scope.values = stage; 
	$scope.save = function() {
		var raw = angular.copy($scope.values);
		raw.material = raw.material.id;
		if (raw.parent) raw.parent = raw.parent.id;
		delete raw._embedded;
		angular.forEach(raw.children, function(item, i) { 
			raw.children[i] = {id:item.id}; 
		});
		console.log(raw);
		var uri;  
		uri = stage._embedded ? stage._embedded._links.edit.href : process._embedded._links.stage.href;
		$resource(uri).save(raw).$promise.then(
			function(data) {
				stage._embedded = data;
				$uibModalInstance.close(data);	
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors;
			},
		);
	};
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};

	$scope.getOperations = function(model, currents) {
		var options = [];
		angular.forEach($scope.operations, function(op, key) {
			if (model.id == op.id || currents.map(function(e) {return e.id}).indexOf(op.id) == -1) {
				options.push(op);	
			}
		});
		options.unshift(_defaultOption);
		return options;
	};
	$scope.addOperation = function() {
		$scope.values.operations.push(angular.copy(_defaultOption));
	};

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

	$scope.parentOptions = function() {
		var options = [];
		var current = stage; 
		while (current.parent) {
			current = current.parent;
		}
		var child = current; 
		options.push(child);
		while (child.children && child.children.length) {
			child = child.children[0];	
			options.push(child);
		}
		return options;
	}

	$scope.changeParent = function() {
		$scope.values.children = $scope.values.parent.children;
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
});

App.controller('_OperationTypeModalCtrl', function($scope, $uibModalInstance, $resource, group) {
	console.log(group);
	$scope.values = group;
	$scope.errors = {};
	$scope.save = function() {
		$resource(group._links.edit.href).save($scope.values).$promise.then(
			function(data) {
				group._embedded = data;
				$uibModalInstance.close(data);	
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

App.controller('_OperationModalCtrl', function($scope, $uibModalInstance, $resource, group, op) {
	console.log(group, op);
	$scope._createOption  = _createOption;
	$scope.group  = group;
	$scope.op	  = op;
	$scope.values = op;
	//$scope.values = angular.copy(op);
	$scope.init = function() {
		//$scope.values = angular.copy(op);
		$scope.values.children = [];
		if (op._embedded && op._embedded._embedded && op._embedded._embedded.children) {
			angular.forEach(op._embedded._embedded.children, function(child, i) {
				_child = angular.copy(child);
				_child._embedded = child; 
				$scope.values.children[i] = _child;
			});
		}
		console.log($scope.op, $scope.values);
	}
	$scope.getOperations = function(model, currents) {
		var options = [];
		angular.forEach(group.operations, function(item, key) {
			if (model.id == item.id || currents.map(function(e) {return e.id}).indexOf(item.id) == -1) {
				options.push(item);
			}
		});
		options.unshift($scope._createOption);
		return options;
	}
	$scope.reloadTitle = function() {
		$scope.values.text = "";
		angular.forEach($scope.values.children, function(child) {
			if (child.text) {
				if ($scope.values.text.length) $scope.values.text += " + ";
				$scope.values.text += child.text;
			}
		});
	}
	$scope.mixed = function() {
		if ($scope.values.children.length) {
			$scope.values.children = [];
		}
		else {
			$scope.values.children = [
				angular.copy($scope._createOption),
				angular.copy($scope._createOption),
			];
		}
	}
	$scope.addChild = function() {
		$scope.values.children.push(angular.copy(_createOption));
	}
	$scope.save = function() {
		var raw = {operation: angular.copy($scope.values)};
		angular.forEach(raw.operation.children, function(child) {
			child.type = group.id;
		});
		delete raw.operation.type;
		var resource;
		if (op._embedded) {
			resource = $resource(op._embedded._links.edit.href);
		}
		else {
			resource = $resource(group._embedded._links.operation.href);
		}
		resource.save(raw).$promise.then(
			function(data) {
				$uibModalInstance.close(data);	
				op._embedded = data;
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors.operation;
			},
		);
	};
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
	$scope.init();
});
