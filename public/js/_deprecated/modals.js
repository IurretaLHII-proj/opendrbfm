App.controller('_HintTypeModalCtrl', function($scope, $uibModalInstance, $resource, type) {
	$scope.type   = type;
	$scope.values = JSON.parse(JSON.stringify(type));
	$scope.errors = {};
	$scope.save = function() {
		$resource(type.id ? type.links.getHref('edit') : type.operation.links.getHref('hint'))
			.save($scope.values).$promise.then(
				function(data) {
					type.load(data);
					$uibModalInstance.close();	
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

App.controller('_NoteModalCtrl', function($scope, $uibModalInstance, $resource, note, link)
{
	$scope.errors = {};
	$scope.note   = note;
	$scope.values = JSON.parse(JSON.stringify(note));
	$scope.save = function() {
		var uri = note.id ? note.links.getHref('edit') : link.href;
		return $resource(uri).save({note:$scope.values}).$promise.then(
			function(data) { 
				note.load(data);
				$uibModalInstance.close(data);	
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors.note;
			}	
		);
	}

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_HintModalCtrl', function($scope, $uibModal, $uibModalInstance, $resource, api, stage, hint)
{
	$scope.errors = {};
	$scope.stage  = stage;
	$scope.values = JSON.parse(JSON.stringify(hint));
	$scope.operations = stage.operations.concat([{id:null, name:' --Select One-- '}]);

	$scope.loadOperationTypes = function() {
		$scope.operationTypes = [{id:null, name:' --Select One-- '}, {id:-1, name: ' --Create New-- '}];
		if ($scope.values.operation) {
			angular.forEach(stage.operations, function(op) {
				if (op.id == $scope.values.operation) {
					$resource(op.links.getHref('hints')).get().$promise.then(function(data) {
						$scope.operationTypes = $scope.operationTypes.concat(data._embedded.items);
					});
				}	
			});
		}
	}

	$scope.createHint = function() {
	 	if ($scope.values.type < 0) {	
			let type = new MAHintType();
			var op = $scope.operations.find(e => {return e.id == $scope.values.operation})
			type.operation = op;
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
				controller: '_HintTypeModalCtrl',	
				size: 'lg',
				scope: $scope,
				resolve: {type: type},
			});

			modal.result.then(
				function(res) {
					$scope.operationTypes.push(type);
					$scope.values.type = type.id;
				},
				function() {}
			);
		}
	}

	$scope.save = function() {
		var uri = hint.id ? hint.links.getHref('edit') : hint.stage.links.getHref('hint');
		return $resource(uri).save($scope.values).$promise.then(
			function(data) { 
				hint.load(data);
				$uibModalInstance.close(data);	
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors;
			}	
		);
		/*var raw  = angular.copy($scope.values);
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
		);*/
	};
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

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_RenderModalCtrl', function($scope, $uibModalInstance, $resource, $filter, simulation) {
	$scope.stateOptions = _stateOptions;
	$scope.values  		= JSON.parse(JSON.stringify(simulation));
	$scope.values.state	= $scope.values.state.toString();
	$scope.values.when  = $scope.values.when ? new Date($scope.values.when) : null;
	$scope.errors  		= {};

	$scope.save = function() {
		var raw = angular.copy($scope.values);
		raw.when = raw.when ? $filter('date')(new Date($scope.values.when), 'yyyy-MM-dd') : null;
		$resource(simulation.links.getHref('render')).save(raw).$promise.then(
			function(data) {
				simulation.load(data);
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
	console.log($scope.stateOptions, $scope.values, simulation);
});

App.controller('_ProcessModalCtrl', function($scope, $uibModalInstance, $resource, process) {
	$scope.errors  = {};
	$scope.values  = JSON.parse(JSON.stringify(process));
	$scope.complexityOptions = [
		{id:MAProcess.COMPLEXITY_LOW,  	 name: "LOW"},
		{id:MAProcess.COMPLEXITY_MEDIUM, name: "MEDIUM"},
		{id:MAProcess.COMPLEXITY_HIGH,	 name: "HIGH"},
	];

	$scope.customers = [];
	$scope.loadCustomers = function() {
		$resource('/customer/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, function(item) {
					$scope.customers.push(new MAUser(item));
				});
			}, 
			function(err){}
		);
	};
	$scope.save = function() {
		var war = $scope._addWarning("Updating...");
		$resource(process.links.getHref('edit')).save($scope.values).$promise.then(
			function(data){
				$scope._closeWarning(war);
				process.load(data);
				$uibModalInstance.close(data);	
			}, 
			function(err){
				$scope._closeWarning(war);
				console.log(err);
				$scope.errors = err.data.errors;
			}
		);
	};
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_VersionModalCtrl', function($scope, $uibModalInstance, $resource, version) {
	$scope.version  = version;
	$scope.values   = JSON.parse(JSON.stringify(version));
	$scope.errors   = {};
	$scope.init 	= function() {
		$scope.materials = [{id:null, name:" --Select Material-- "}];
		$resource('/process/material/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.materials.push(new MAMaterial(item));
				});
			}, 
			function(err){}
		);
		console.log(version,$scope.values);
	}
	$scope.save   = function() {
		var uri = version.id ? version.links.getHref('edit') : version.process.links.getHref('version');
		console.log(version, $scope.values, uri);
		var war = $scope._addWarning("Saving...");
		$resource(uri).save($scope.values).$promise.then(
			function(data){
				$scope._closeWarning(war);
				version.load(data);
				$uibModalInstance.close(data);
			}, 
			function(err){
				$scope._closeWarning(war);
				console.log(err);
				$scope.errors = err.data.errors;
			}
		);
	}
	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_StageModalCtrl', function($scope, $uibModalInstance, $resource, $uibModal, stage) {
	$scope.stage  = stage;
	$scope.values = JSON.parse(JSON.stringify(stage));
	$scope.errors = {};
	$scope.dflt   = {id:null, name:" --Select one-- "};
	$scope.nw     = {id:-1,   name:" --Create new-- "};
	console.log(stage, $scope.values);

	$scope.init = function() {
		$scope.materials      		 = [$scope.dflt];
		$scope.operationTypes 		 = [$scope.dflt];
		$scope.stageOptions 		 = []; 
		$scope.values.operationTypes = [];
		if (!$scope.values.operations.length) {
			$scope.values.operations 	 = [$scope.dflt];
			$scope.values.operationTypes = [$scope.dflt];
		}
		$resource('/process/material/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.materials.push(new MAMaterial(item));
				});
			}, 
			function(err){}
		);
		$resource('/process/op/type/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					let type = MAOperationType.fromJSON(item);
					$scope.operationTypes.push(type);
					angular.forEach($scope.values.operations, function(op, i) {
						if (undefined !== (a = type.operations.find(e => {return e.id == op.id;}))) {
							$scope.values.operationTypes[i] = type;
						}
					});
				});
			}, 
			function(err){}
		);
	}

	$scope.createOperation = function(i) {
		console.log($scope.values);
	 	if ($scope.values.operations[i].id < 0) {	
			let op   = new MAOperation();
			var type = $scope.operationTypes.find(e => {return e.id == $scope.values.operationTypes[i].id})
			op.type = type;
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/operation-form.html',
				controller: '_OperationModalCtrl',	
				scope: $scope,
				size: 'lg',
				resolve: {op: op},
			});

			modal.result.then(
				function(res) {
					type.addOperation(op);
					$scope.values.operations[i] = op;
				},
				function() {}
			);
		}
	}

	$scope.addOperation = function() {
		$scope.values.operationTypes.push($scope.dflt);
		$scope.values.operations.push($scope.dflt);
	};

	$scope.save = function() {
		$resource(stage.id ? stage.links.getHref('edit') : stage.version.links.getHref('stage'))
			.save({stage:$scope.values}).$promise.then(
				function(data) {
					stage.load(data);
					$uibModalInstance.close();	
				},
				function(err) {
					console.log(err);
					$scope.errors = err.data.errors.stage;
				},
			);
	}

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
	/*$scope.init = function() {
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
    }*/
});

App.controller('_OperationTypeModalCtrl', function($scope, $uibModalInstance, $resource, group) {
	$scope.values  = JSON.parse(JSON.stringify(group));
	$scope.errors = {};
	console.log($scope.values, group);
	
	$scope.save = function() {
		var war = $scope._addWarning("Updating...");
		$resource(group.links.getHref('edit')).save($scope.values).$promise.then(
			function(data) {
				$scope._closeWarning(war);
				group.load(data);
				$uibModalInstance.close(data);	
			},
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.errors = err.data.errors;
			},
		);
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_OperationModalCtrl', function($scope, $uibModalInstance, $resource, op) {
	$scope.op	  = op;
	$scope.values =  JSON.parse(JSON.stringify(op));
	console.log(op, $scope.values);

	$scope.getOperations = function(i) {
		$scope.operations = [{id: null, name: " --Select One-- "}];
		angular.forEach(op.type.operations, e => {
			var index = $scope.values.children.map(c => {return c.id}).indexOf(e.id);
			if (e.children.length == 0 && (index == -1 || index == i) ) {
				$scope.operations.push(JSON.parse(JSON.stringify(e)));
			}
		});
		return $scope.operations;
	}
	
	$scope.save = function() {
		var war = $scope._addWarning("Updating...");
		var uri  = op.id ? op.links.getHref('edit') : op.type.links.getHref('operation');
		$resource(uri).save({operation:$scope.values}).$promise.then(
			function(data) {
				$scope._closeWarning(war);
				op.load(data);
				$uibModalInstance.close(data);	
			},
			function(err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.errors = err.data.errors.operation;
			},
		);
	};

	$scope.reloadTitle = function() {
		console.log($scope.values);
		$scope.values.name = "";
		angular.forEach($scope.values.children, function(child) {
			if ($scope.values.name.length) $scope.values.name += " + ";
			$scope.values.name += child.name;
		});
	}

	$scope.mixed = function() {
		if ($scope.values.children.length) {
			$scope.values.children = [];
		}
		else {
			$scope.values.children = [
				{id: null, name: " --Select One-- "},
				{id: null, name: " --Select One-- "},
			];
		}
		$scope.reloadTitle();
	}

	$scope.addChild = function() {
		$scope.values.children.push({id: null, name: " --Select One-- "});
		$scope.reloadTitle();
	}

	$scope.rmChild = function(i) {
		$scope.values.children.splice(i, 1);
		$scope.reloadTitle();
	}

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};

	/*$scope.init = function() {
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
	$scope.init();*/
});

App.controller('_SimulationModalCtrl', function($scope, $uibModalInstance, $resource, simulation)
{
	$scope.values  = JSON.parse(JSON.stringify(simulation));
	$scope.errors  = {};

	$scope.save = function() {
		var uri  = simulation.id ? 
					simulation.links.getHref('edit') : simulation.hint.links.getHref('simulate');
		$resource(uri).save($scope.values).$promise.then(
			function(data) {
				simulation.load(data);
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

	$scope.previousStages = function() {
		$scope.previouses = [];
		var stage = simulation.hint.stage;
		while (stage.parent) {
			$scope.previouses.push(stage.parent);
			stage = stage.parent;
		}
		console.log($scope.previouses);
	}
	
	$scope.nextStages = function() {
		$scope.nexts = [];
		var stage = simulation.hint.stage;
		while (stage.children.length) {
			$scope.nexts.push(stage.children[0]);
			stage = stage.children[0];
		}
		console.log($scope.nexts);
	}

	$scope.nextSimulations = function(i) {
		$scope.nSimulations = $scope.nexts.find(e => {return e.id == $scope.values.nexts[i]}).hints;
		$scope.nSimulations.push({id:0, name:" --Select One-- "});
		console.log($scope.nSimulations);
	}
});

App.controller('__SimulationModalCtrl', function($scope, $uibModalInstance, $resource, simulation)
{
	$scope.values  = JSON.parse(JSON.stringify(simulation));
	$scope.errors  = {};

	$scope.save = function() {
		var uri  = simulation.id ? 
					simulation.links.getHref('edit') : simulation.hint.links.getHref('simulate');
		$resource(uri).save($scope.values).$promise.then(
			function(data) {
				simulation.load(data);
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

	$scope.addReason = function() {
		/*var opt = {
			stage: _noneOption,
			simulation: angular.copy(_createOption),
		}
		$scope.values.reasons.push(opt);*/
		$scope.values.reasons.push(new MANote({}));
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

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
	console.log(simulation, $scope.values);
});
