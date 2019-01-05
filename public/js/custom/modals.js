App.controller('_HintTypeModalCtrl', function($scope, $uibModalInstance, $resource, op, type) {
	console.log(op, type);
	$scope.operation = op;
	$scope.values = type;
	$scope.errors = {};
	$scope.save = function() {
		$resource(type._embedded ? type._embedded._links.edit.href : op._embedded._links.hint.href)
			.save($scope.values).$promise.then(
				function(data) {
					angular.copy(data, type);
					type._embedded = data;
					$uibModalInstance.close();	
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
		raw.operation = raw.operation._embedded ? raw.operation.id : null;
		delete raw.stage;
		console.log(raw);
		var uri  = hint._embedded ? hint._embedded._links.edit.href : stage._embedded._links.hint.href;
		$resource(uri).save(raw).$promise.then(
			function(data) {
				//FIXME
				//angular.copy(data, hint);
				hint.id = data.id;
				hint.name = data.name;
				hint._embedded = data;
				$uibModalInstance.close(data);	
				return data;
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors;
				return err;
			},
		);
	};
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
	$scope.loadOperationTypes = function() {
		$scope.operationTypes = [_defaultOption, _createOption];
		if (hint.operation) {
			api.operation.getHints(hint.operation).then(function(data) {
				$scope.operationTypes = $scope.operationTypes.concat(data._embedded.items);
				//angular.forEach(data._embedded.items, function(type) {
				//	var _type = angular.copy(type);
				//	_type._embedded = type;
				//	$scope.operationTypes.push(_type);
				//});
			});
		}
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
					$scope.operationTypes.push(_type);
					hint.type = _type;
				},
				function() {}
			);
		}
	}
	/*$scope.getPrevs = function(model) {
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
	}*/
});

App.controller('_RenderModalCtrl', function($scope, $uibModalInstance, $resource, $filter, simulation) {
	console.log(simulation);
	$scope.stateOptions = _stateOptions;
	$scope.errors = {};
	$scope.values = simulation; 
	$scope.values.state = simulation.state.toString();
	$scope.save = function() {
		var raw = angular.copy($scope.values);
		delete raw.hint;
		raw.when = raw.when ? $filter('date')(new Date($scope.values.when), 'yyyy-MM-dd') : null;
		$resource(simulation._embedded._links.render.href).save(raw).$promise.then(
			function(data) {
				simulation._embedded = data;
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
});

App.controller('_StageModalCtrl', function($scope, $uibModalInstance, $resource, api, process, stage) {
	$scope.errors = {};
	$scope.values = stage; 
	$scope.values.operationTypes = [_defaultOption]; 
	$scope._materials 	   		 = [_defaultOption];
	$scope._operationTypes 		 = [_defaultOption];
	_noneOption.getName = function() {return this.name;};

	$scope.init = function() {
		api.operationType.getAll().then(function(items) {
			angular.forEach(items, function(type) {
				var _type = angular.copy(type);
				_type._embedded  = type;
				_type.operations = [];
				angular.forEach(type._embedded.operations, function(op) {
					var _op 	  = angular.copy(op);
					_op._embedded = op;
					_op.type 	  = _type;
					_type.operations.push(_op);

					var i = $scope.values.operations.map(function(item) {return item.id}).indexOf(op.id);
					if (i >= 0) {
						$scope.values.operations[i] 	= _op;
						$scope.values.operationTypes[i] = _type;
					}
				});
				$scope._operationTypes.push(_type);
			});
		});
		api.material.getAll().then(function(data) {
			angular.forEach(data, function(m) { $scope._materials.push(m); });
		});
		if (stage.parent.id) $scope.values._parent = stage.parent;
		console.log(stage);
	}

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};

	var _previouses = [];
	var _nexts = [];
	$scope.stageOptions = []; 
	$scope.loadStages   = function() {
		var item = stage;
		while (item.parent.id) {
			_previouses.unshift(item.parent);
			item = item.parent;
		}
		var item = stage;
		while (item.children.length) {
			_nexts.push(item.children[0]);
			item = item.children[0];
		}
		$scope.stageOptions = _previouses.concat(_nexts);
		$scope.stageOptions.unshift(_noneOption);
	}
	$scope.save = function() {
		var higher	 = $scope.stageOptions.length > 1 ? $scope.stageOptions[1] : $scope.stageOptions[0];
		var selected = stage._parent;

		if (selected && !selected.id) {
			if (stage.id && stage.parent.id) {
				var child = higher;
				while (child.children.length && child.children.indexOf(stage) == -1) {
					var child = child.children[0];
				}
				child.children = stage.children;
				angular.forEach(child.children, function(item) { item.parent = child; });
			}

			if ($scope.version && stage != $scope.version) {
				stage.children = [$scope.version];
				angular.forEach(stage.children, function(item) { item.parent = stage; });
				var i = stage.process.versions.indexOf($scope.version);
				stage.process.versions[i] = stage;
			}
			else
				stage.process.versions.push(stage);

			stage.parent = _noneOption;
			$scope.setVersion(stage);
			$scope.setCurrent(stage);
			//console.log(stage);
		}
		else if (selected && selected.id && selected.children.indexOf(stage) == -1) {
			if (!stage.parent.id) {
				stage.children[0].parent = _noneOption;
				var i = stage.process.versions.indexOf(stage);
				stage.process.versions[i] = stage.children[0];
				$scope.setVersion(stage.children[0]);
			}
			else {
				var child = higher;
				while (child.children.length && child.children.indexOf(stage) == -1) {
					var child = child.children[0];
				}
				child.children = stage.children;
				angular.forEach(child.children, function(item) { item.parent = child; });
			}
			stage.children = selected.children;
			angular.forEach(stage.children, function(item) { item.parent = stage; });
			selected.children = [stage];
			stage.parent = selected;
			$scope.setCurrent(stage);
			//console.log(stage);
		}
		else if(!selected) {
			stage.process.versions.push(stage);
			$scope.setVersion(stage);
		}

		if (!stage.id) {
			api.stage.save(stage).then(
				function(data){
					stage.id = data.id;
					stage._embedded = data;
					$uibModalInstance.close(data);	
					$scope.addSuccess("Saved succesfully");
				}, 
				function(err){
					console.log(err);
					$scope.errors = err.data.errors['stage'];
				}
			);
		}
		else {
			var curr = $scope.version;
			var raw  = {stages: [api.stage.prepare(curr)]};
			while (curr.children.length) {
				curr = curr.children[0];
				raw.stages.push(api.stage.prepare(curr));
			}
			$resource(stage.process._links.stages.href).save(raw).$promise.then(
				function(data){
					$scope.addSuccess("Saved succesfully");
					$uibModalInstance.close(data);	
				}, 
				function(err){
					$scope.errors = err.data.errors;
					$scope.addError(err.data.title);
				}
			);
		}
	}

	$scope.getOperations = function(index) {
		var options = [_defaultOption];
		if (!($scope.values.operationTypes && $scope.values.operationTypes[index])) { 
			return options;
		}
		angular.forEach($scope.values.operationTypes[index].operations, function(op, key) {
			var i = $scope.values.operations.indexOf(op);
			if (i == -1 || i == index) { 
				options.push(op);	
			}
		});
		return options;
	};

	$scope.addOperation = function() {
		$scope.values.operationTypes.push(_defaultOption);
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
				console.log(err);
				$scope.errors = err.data.errors;
			},
		);
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_OperationModalCtrl', function($scope, $uibModalInstance, $resource, group, op) {
	console.log(op);
	$scope.group  = group;
	$scope.op	  = op;
	$scope.values = op;
	$scope.init = function() {
		angular.forEach(op.children, function(child, index) {
			var i = group.operations.map(function(item) {return item.id}).indexOf(child.id);
			if (i >= 0) {
				op.children[index] = group.operations[i];
			}
		});
	}
	$scope.getOperations = function(index) {
		var options = [_defaultOption];
		angular.forEach(group.operations, function(item) {
			if (!item.children.length) { 
				var i = op.children.indexOf(item);
				if (i == -1 || i == index) { 
					options.push(item);	
				}
			}
		});
		return options;
	}

	$scope.reloadTitle = function() {
		op.text = "";
		angular.forEach(op.children, function(child) {
			if (child.text) {
				if (op.text.length) op.text += " + ";
				op.text += child.text;
			}
		});
	}
	$scope.mixed = function() {
		if (op.children.length) {
			op.children = [];
		}
		else {
			op.children = [
				angular.copy(_defaultOption),
				angular.copy(_defaultOption),
			];
		}
	}
	$scope.save = function() {
		var war = $scope._addWarning("Saving..");
		var raw = {operation: angular.copy($scope.values)};
		angular.forEach(raw.operation.children, function(child) {
			child.type = group.id;
		});
		delete raw.operation.type;
		var url = op._embedded ? op._embedded._links.edit.href : group._embedded._links.operation.href;
		$resource(url).save(raw).$promise.then(
			function(data) {
				$scope._closeWarning(war);
				$uibModalInstance.close(data);	
				op._embedded = data;
			},
			function(err) {
				$scope._closeWarning(war);
				console.log(err);
				$scope.errors = err.data.errors.operation;
			},
		);
	};
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
	$scope.addChild = function() {
		op.children.push(angular.copy(_defaultOption));
		$scope.reloadTitle();
	}
	$scope.rmChild = function(i) {
		op.children.splice(i, 1);
		$scope.reloadTitle();
	}
	$scope.init();
});

App.controller('_SimulationModalCtrl', function($scope, $uibModalInstance, $resource, simulation)
{
	var hint = simulation.hint;
	$scope.values = simulation;
	$scope.errors = {};
	$scope.hint = hint;
	$scope.save = function() {
		angular.forEach($scope.values.reasons, function(item) {
			//if (item.id < 0) { item.id=1; item.name=item.text; }//item.hint: hint}
		});
		angular.forEach($scope.values.influences, function(item) {
			//if (item.id < 0) { item.id=1; item.name=item.text; }//item.hint: hint}
		});
		var raw  = angular.copy($scope.values);
		delete raw.hint;
		var uri  = simulation._embedded ? 
					simulation._embedded._links.edit.href : hint._embedded._links.simulate.href;
		$resource(uri).save(raw).$promise.then(
			function(data) {
				simulation._embedded = data;
				$uibModalInstance.close(data);	
				return data;
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors;
				return err;
			},
		);
	};
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};

	$scope.addReason = function() {
		var opt = {
			stage: _noneOption,
			simulation: angular.copy(_createOption),
		}
		$scope.values.reasons.push(opt);
	}

	$scope.addInfluence = function() {
		var opt = {
			stage: _noneOption,
			simulation: angular.copy(_createOption),
		}
		$scope.values.influences.push(opt);
	}

	$scope.reasonStages = function() {
		var options = [];	
		var current = hint.stage;
		while (current.parent) {
			current = current.parent;
			options.push(current);
		}
		options.push(_noneOption);
		return options;
	}

	$scope.influenceStages = function() {
		var options = [];	
		var current = hint.stage;
		while (current.children.length) {
			current = current.children[0];
			options.push(current);
		}
		options.push(_noneOption);
		return options;
	}

	$scope.reasonOptions = function(index, current) {
		var options = $scope.stageOptions(current);
		options.push($scope.values.reasons[index].simulation);
		console.log(options);
		return options;
	};
	$scope.influenceOptions = function(index, current) {
		var options = $scope.stageOptions(current);
		angular.forEach(options, function(s) {
		//	if ($scope.values.influences.indexOf(s) == -1) {
		//		options.push(s);
		//	}
		});
		options.push($scope.values.influences[index].simulation);
		return options;
	};
	$scope.stageOptions = function(current) {
		var options = [];	
		angular.forEach(current.hints, function(h) {
			options = options.concat(h.simulations);
		});
		return options;
	};

	/*
	 	$scope.influenceOptions = function(index) {
		var options = [];	
		var current = hint.stage;
		while (current.children.length) {
			current = current.children[0];
			angular.forEach(current.hints, function(h) {
				if ($scope.values.influences.indexOf(h) == -1) {
					options.push(h);
				}
			});
		}
		options.push($scope.values.influences[index]);
		//console.log(options);
		return options;
	};
	*/
});
