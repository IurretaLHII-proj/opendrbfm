var _createOption  = {id:-1,   name:'--- Create new ---'};
var _defaultOption = {id:null, name:'--- Select one ---'};
var _stateOptions = [
		{id:1,  name: "IN PROGRESS"},
		{id:2,  name: "FINISHED"},
		{id:-1, name: "NOT NECESSARY"},
		{id:-2, name: "CANCELlED"},
	];

App.controller('_DetailCtrl', function($scope, $resource, $uibModal, $timeout, api) {

	var _initVersion = function(version, items) {
		version.children = [];
		while(items.length) {
			item = _prepareStage(items.shift(items));
			item.parent = version;
			version.children.push(item);
			_initVersion(item, items);
		}
	}
	var _prepareOperation = function(op) {
		_op = angular.copy(op);
		_op._embedded = op;
		return _op;
	}
	
	var _prepareHint = function(hint) {
		_hint = angular.copy(hint);
		_hint._embedded   = hint;
		_hint.operation   = hint._embedded.operation;
		_hint.type 		  = hint._embedded.type;
		_hint.parents     = [];
		_hint.reasons     = [];
		_hint.suggestions = [];
		_hint.influences  = [];
		_hint.when  = _hint._embedded.when ? new Date(_hint._embedded.when*1000) : null,
		_hint.state = _hint.state.toString();
		angular.forEach (hint._embedded.parents, function(prt, p) {
			_hint.parents[p] = prt;
		});
		angular.forEach (hint._embedded.reasons, function(note, i) {
			_hint.reasons[i] = angular.copy(note);
			_hint.reasons[i]._embedded = note;
		});
		angular.forEach (hint._embedded.suggestions, function(note, i) {
			_hint.suggestions[i] = angular.copy(note);
			_hint.suggestions[i]._embedded = note;
		});
		angular.forEach (hint._embedded.influences, function(note, i) {
			_hint.influences[i] = angular.copy(note);
			_hint.influences[i]._embedded = note;
		});
		return _hint;
	}
	var _prepareStage = function(stage) {
		_stage = angular.copy(stage);
		_stage._embedded = stage;
		_stage.material  = stage._embedded.material;
		_stage.operations = [];
		angular.forEach(stage._embedded.operations, function(op, i) {
			_stage.operations[i] = _prepareOperation(op);
		});
		if (!_stage.operations.length) _stage.operations.push({});

		_stage.hints = [];
		angular.forEach(stage._embedded.hints, function(hint, i) {
			_stage.hints[i] = _prepareHint(hint);
		});
		//if (!_stage.hints.length) $scope.addHint(_stage);

		_stage.images = [];
		angular.forEach(stage._embedded.images, function(image, i) {
			_stage.images[i] = image;
		});	
		return _stage;
	}

	$scope.errors = {};
	$scope.values = {versions: []};

	$scope.init = function(item, values) {
		if (item) {
			$scope.values = angular.copy(item);
			$scope.values.created   = new Date($scope.values.created*1000);
			$scope.values._embedded = item;
			$scope.values.versions  = [];
			if (item._embedded &&
				item._embedded.versions && 
				item._embedded.versions.length) {

				angular.forEach(item._embedded.versions, function(version, index) {
					_stage = _prepareStage(version);
					$scope.values.versions[index] = _stage;
					if (!$scope.current) {
						//$scope.version = _stage;
						//$scope.current = _stage;
						$scope.loadVersion(_stage);
					}
				});
			}
			else {
				//$scope.addVersion();
			}
		}

		if (values) {
			angular.merge($scope.values, values);
		}

		//api.operation.getCollection().then(function(data) {
		//	$scope.operations = data._embedded.items;
		//});

		console.log(item, $scope.values, $scope.current);
	}

	$scope.loadVersion = function(version) {
		if (!version.children) {
			$resource(version._links.children.href).get().$promise.then(
				function (data) {
					_initVersion(version, data._embedded.items);
				},
				function (err) {
					console.log(err);
				}	
			);
		}
		$scope.version = version;
		$scope.current = version;
	}

	$scope.setCurrent = function(stage) {
		$scope.current = stage;
	};

	/** Stage **/
	$scope.addStage = function(parentStage) {
		var _stage = {
			version: $scope.values.versions.length+1,
			level: 1,
			hints:[],
			images:[],
			children:[],
			operations:[_defaultOption],
			_links: {image: {href: '/process/stage/image/json'}}
		};
		if (parentStage) {
			_stage.parent   = parentStage._embedded; //id
			_stage.level    = parentStage.level + 1;
			_stage.version  = parentStage.version;
			_stage.material = parentStage.material;
		}
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/stage-form.html',
			controller: '_StageModalCtrl',	
			size: 'lg',
			resolve: {stage: _stage, process: $scope.values._embedded,}
		});

		modal.result.then(
			function(res) {
				if (parentStage) parentStage.children.push(_stage);
				else $scope.values.versions.push(_stage);
				$scope.current = _stage;
				if (!$scope.current.parent) {
					$scope.version = _stage;
				}
				$scope.addSuccess("Saved succesfully");
			}
		);
	}

	$scope.editStage = function(stage) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/stage-form.html',
			controller: '_StageModalCtrl',	
			size: 'lg',
			resolve: {stage: stage, process: $scope.values._embedded,}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			}
		);
	}

	/** Hints **/
	$scope.addHint = function() {
		var _hint = {};
		_hint.operation   = $scope.current._embedded._embedded.operations[0];
		_hint.type		  = _defaultOption;
		_hint.parents     = [];
		_hint.reasons     = [];
		_hint.suggestions = [];
		_hint.influences  = [];
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-form.html',
			controller: '_HintModalCtrl',	
			size: 'lg',
			resolve: {hint: _hint, stage: $scope.current}
		});

		modal.result.then(
			function(res) {
				$scope.current.hints.push(_hint);
				$scope.addSuccess("Saved succesfully");
			}
		);
	}

	$scope.editHint = function(hint) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-form.html',
			controller: '_HintModalCtrl',	
			size: 'lg',
			resolve: {hint: hint, stage: $scope.current}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			}
		);
	}

	$scope.simuleHint = function(hint) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/render-form.html',
			controller: '_RenderModalCtrl',	
			size: 'lg',
			resolve: {hint : hint}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

});

App.controller('_HintTypeModalCtrl', function($scope, $uibModalInstance, $resource, op, type) {
	$scope.operation = op;
	$scope.values = type;
	$scope.errors = {};
	$scope.save = function() {
		$resource(type._embedded ? type._embedded._links.edit.href : op._links.hint.href)
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
	console.log(stage);
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
		delete raw.children;
		var uri  = stage._embedded ? stage._embedded._links.edit.href : process._links.stage.href;
		$resource(uri).save(raw).$promise.then(
			function(data) {
				stage._embedded = data;
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

App.controller('_OperationTypesCtrl', function($scope, $resource, $uibModal, api) {
		
	$scope.values = [];
	$scope.init = function(collection) {
		$scope.collection = collection._embedded.items;
		angular.forEach($scope.collection, function(item, index) {
			var group = angular.copy(item);
			group._embedded = item;
			group.operations = [];
			angular.forEach(group._embedded._embedded.operations, function(op, i) {
				group.operations[i] = angular.copy(op);
				group.operations[i]._embedded = op;
			});
			$scope.values.push(group); 
		});
		console.log(collection, $scope.values);
	};

	$scope.showHints = function(op) {
		if (!op.loaded) {
			op.loaded = true;
			op.hints  = [];
			api.operation.getHints(op._embedded).then(function(data) {
				angular.forEach(data._embedded.items, function(item, i) {
					op.hints[i] = angular.copy(item);
					op.hints[i]._embedded = item;
					
				});
				op._sh=true;
			});
		}
		op._sh = !op._sh;
		console.log(op);
	};

	$scope.editGroup = function(group) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-type-form.html',
			controller: '_OperationTypeModalCtrl',	
			size: 'lg',
			resolve: {group: group}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			}
		);
	}
	$scope.addOperation = function(group) {
		var op = {type: group,};
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			resolve: {group: group, op:op}
		});

		modal.result.then(
			function(res) {
				group.operations.push(op);
				$scope.addSuccess("Saved succesfully");
			}
		);
	}
	$scope.editOperation = function(group, op) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			resolve: {group: group, op:op}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			}
		);
	}
	$scope.deleteOperation = function(group, op) {
		api.operation.delete(op._embedded).then(
			function (data) {
        		group.operations.splice(group.operations.indexOf(op), 1);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				$scope.addError(err.data.title);
			}	
		);
	}
	$scope.addHint = function(op) {
		var _type = {};
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
			controller: '_HintTypeModalCtrl',	
			size: 'lg',
			resolve: {op: op, type: _type} 
		});

		modal.result.then(
			function(res) {
				op.hints.push(_type);
				$scope.addSuccess("Saved succesfully");
			},
		);
	}
	$scope.editHint = function(op, hint) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
			controller: '_HintTypeModalCtrl',	
			size: 'lg',
			resolve: {op: op, type: hint} 
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
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
