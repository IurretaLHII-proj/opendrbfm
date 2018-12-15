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
			_stage.parent   = parentStage;
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

App.controller('_HintTypeModalCtrl', function($scope, $uibModalInstance, $resource, op) {
	$scope.operation = op;
	$scope.values = {};
	$scope.errors = {};
	$scope.save = function() {
		$resource(op._links.hint.href).save($scope.values).$promise.then(
			function(data) {
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
	 	if ($scope.values.type && $scope.values.type.id < 0) {	
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
				controller: '_HintTypeModalCtrl',	
				size: 'lg',
				resolve: {op : $scope.values.operation},
			});

			modal.result.then(
				function(res) {
					console.log(res);
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
		$scope.values.operations.push(_defaultOption);
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
