App.controller('_NoteModalCtrl', function($scope, $uibModalInstance, $resource, note, link)
{
	console.log(note);
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

App.controller('_HintReasonRelationModalCtrl', function($scope, $uibModalInstance, $resource, relation, extended) {
	$scope.modal			 = $uibModalInstance;
	$scope.relation 	  	 = relation;
	$scope.values  	  		 = JSON.parse(JSON.stringify(relation));
	console.log(relation, $scope.values);
	$scope.errors			 = {};
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	let hint			     = relation.reason.hint;
	let stage 		  	     = hint.stage;
	$scope.dflt    	  	     = dflt;

	angular.extend($scope, extended);
	$scope.init(stage);
	$scope.save = function() {
		console.log($scope.values);
		var uri = relation.id ? 
			relation.links.getHref('edit') : relation.reason.links.getHref('relation');
		return $resource(uri).save({relation:$scope.values}).$promise.then(
			function(data) { 
				relation.load(data);
				console.log(data, relation);
				$uibModalInstance.close(data);	
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors.relation;
			}	
		);
	};
});

App.controller('_HintInfluenceRelationModalCtrl', function($scope, $uibModalInstance, $resource, relation, extended) {
	$scope.modal			 = $uibModalInstance;
	$scope.relation 	  	 = relation;
	$scope.values  	  		 = JSON.parse(JSON.stringify(relation));
	console.log(relation, $scope.values);
	$scope.errors			 = {};
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	let hint			     = relation.influence.reason.hint;
	let stage 		  	     = hint.stage;
	$scope.dflt    	  	     = dflt;

	angular.extend($scope, extended);
	$scope.init(stage);
	$scope.save = function() {
		console.log(relation, $scope.values);
		var uri = relation.id ? 
			relation.links.getHref('edit') : relation.influence.links.getHref('relation');
		return $resource(uri).save({relation:$scope.values}).$promise.then(
			function(data) { 
				relation.load(data);
				console.log(data, relation);
				$uibModalInstance.close(data);	
			},
			function(err) {
				console.log(err);
				$scope.errors = err.data.errors.relation;
			}	
		);
	};
});

App.controller('_HintReasonModalCtrl', function($scope, $uibModalInstance, $resource, reason, extended) {
	$scope.modal			 = $uibModalInstance;
	$scope.reason 	  		 = reason;
	$scope.values  	  		 = JSON.parse(JSON.stringify(reason));
	$scope.errors			 = {influences:[]};
	//angular.forEach($scope.values.influences, (e, i) => {$scope.errors.influences[i] = {}});
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	var crto       	  	     = {id:-1,   name:" --Create new-- "};
	let hint			     = reason.hint;
	let stage 		  	     = hint.stage;
	$scope.users  			 = [{id:null, name:" --Who-- "}];

	$resource('/user/json').get().$promise.then(
		function(data){
			angular.forEach(data._embedded.items, item => {
				$scope.users.push(new MAUser(item));
			});
		}, 
		function(err){}
	);

	angular.extend($scope, extended);
	$scope.init(stage);
	$scope.dflt    	  	     = dflt;
	$scope.save = function() {
		var uri = reason.id ? reason.links.getHref('edit') : reason.hint.links.getHref('reason');
		return $resource(uri).save($scope.values).$promise.then(
			function(data) { 
				reason.load(data);
				console.log(data, reason);
				$uibModalInstance.close(data);	
			},
			function(err) {
				$scope.errors.notes = err.data.errors.notes ? err.data.errors.notes : {};
				angular.forEach($scope.errors.influences, (e, i) => {
					if (err.data.errors.influences && err.data.errors.influences[i]) 
						angular.copy(err.data.errors.influences[i], e);
					else 
						angular.copy({}, e);
				});
			}	
		);
	};

	console.log(reason, $scope.values);
});

App.controller('_HintInfluenceModalCtrl', function($scope, $uibModalInstance, $resource, influence, extended) {
	$scope.modal			 = $uibModalInstance;
	$scope.influence  	     = influence;
	$scope.values  	  	     = JSON.parse(JSON.stringify(influence));
	$scope.errors		     = {};
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	let hint			     = influence.reason.hint;
	let stage 		  	     = hint.stage;
	var crto       	  	     = {id:-1,   name:" --Create new-- "};
	$scope.dflt    	  	     = dflt;
	$scope.users  			 = [{id:null, name:" --Who-- "}];

	$resource('/user/json').get().$promise.then(
		function(data){
			angular.forEach(data._embedded.items, item => {
				$scope.users.push(new MAUser(item));
			});
		}, 
		function(err){}
	);

	angular.extend($scope, extended);
	$scope.init(stage);
	$scope.save = function() {
		console.log($scope.values);
		var uri = influence.id ? 
			influence.links.getHref('edit') : influence.reason.links.getHref('influence');
		return $resource(uri).save({influence:$scope.values}).$promise.then(
			function(data) { 
				influence.load(data);
				console.log(data, influence);
				$uibModalInstance.close(data);	
			},
			function(err) {
				$scope.errors = angular.merge({}, err.data.errors.influence);
				console.log($scope.errors);
			}	
		);
	};

	console.log(influence, $scope.values);
});

App.controller('_HintModalCtrl', function($scope, $uibModal, $uibModalInstance, $resource, stage, hint)
{
	$scope.errors = {};
	$scope.hint   = hint;
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
	};

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_RenderModalCtrl', function($scope, $uibModalInstance, $resource, $filter, simulation) {
	$scope.simulation   = simulation;
	$scope.stateOptions = _stateOptions;
	$scope.values  		= JSON.parse(JSON.stringify(simulation));
	$scope.values.state	= $scope.values.state.toString();
	$scope.values.when  = $scope.values.when ? new Date($scope.values.when) : null;
	$scope.errors  		= {};
	console.log(simulation, $scope.values);

    var imagePreview = function(img, index) {
        $scope.values.images[index] = JSON.parse(JSON.stringify(img));
    }

	$scope.init	= function() {
		$scope.users  = [{id:null, name:" --Who-- "}];
		$resource('/user/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.users.push(new MAUser(item));
				});
			}, 
			function(err){}
		);
	}


    $scope.uploadFile = function(ev, entity, index) {
        var data = new FormData;
        var file = ev.target.files[0];
        //var resource = $resource(entity.links.getHref('image'), {}, {
        var resource = $resource('/process/hint/simulation/image/json', {}, {
            upload: {
                method: 'POST',
                transformRequest: function(data) {
                    if (data === undefined)
                      return data;
                    var fd = new FormData();
                    angular.forEach(data, function(value, key) {
                      if (value instanceof FileList) {
                        if (value.length == 1) fd.append(key, value[0]);
                        else {
                          angular.forEach(value, function(file, index) {
                            fd.append(key + '_' + index, file);
                          });
                        }
                      } else fd.append(key, value);
                    });
                    return fd;
                },
                headers: {'Content-Type' : undefined}
            }});
        resource.upload({'file': file}).$promise.then(
             function(data) {
			    imagePreview(new MAImage(data), index);
             },
             function(err) {
				//entity.errors = err.data.errors;
       		 }
        );
    }

	$scope.save = function() {
		var raw = angular.copy($scope.values);
		var uri = simulation.id ? 
			simulation.links.getHref('edit') : simulation.influence.links.getHref('simulation');

		raw.when = raw.when ? $filter('date')(new Date($scope.values.when), 'yyyy-MM-dd') : null;
		$resource(uri).save(raw).$promise.then(
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
	console.log($scope.values, simulation);
});

App.controller('_ProcessModalCtrl', function($scope, $uibModalInstance, $uibModal, $resource, process) {
	$scope.process = process;
	$scope.errors  = {};
	$scope.values  = JSON.parse(JSON.stringify(process));
	$scope.complexityOptions = [
		{id:null, 						 name:' --Select complexity-- '},
		{id:MAProcess.COMPLEXITY_LOW,  	 name: "LOW"},
		{id:MAProcess.COMPLEXITY_MEDIUM, name: "MEDIUM"},
		{id:MAProcess.COMPLEXITY_HIGH,	 name: "HIGH"},
	];

	$scope.plants = [
		{id:null, name:' --Select productive plant-- '},
		{id:-1,   name:' --Create new-- '},
	];
	$scope.loadPlants = function() {
		$resource('/process/plant/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, function(item) {
					$scope.plants.push(MAPlant.fromJSON(item));
				});
			}, 
			function(err){}
		);
	};
	$scope.createPlant = function() {
	 	if ($scope.values.plant < 0) {	
			let plant = new MAPlant();
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/plant-form.html',
				controller: '_PlantModalCtrl',	
				scope: $scope,
				size: 'lg',
				resolve: {plant: plant},
			});

			modal.result.then(
				function(res) {
					$scope.plants.push(plant);
					$scope.values.plant = plant.id;
				},
				function() {}
			);
		}
	}

	$scope.machines = [
		{id:null, name:' --Select machine-- '},
		{id:-1,   name:' --Create new-- '},
	];
	$scope.loadMachines = function() {
		$resource('/process/machine/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, function(item) {
					$scope.machines.push(MAMachine.fromJSON(item));
				});
			}, 
			function(err){}
		);
	};
	$scope.createMachine = function() {
	 	if ($scope.values.machine < 0) {	
			let machine = new MAMachine();
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/machine-form.html',
				controller: '_MachineModalCtrl',	
				scope: $scope,
				size: 'lg',
				resolve: {machine: machine},
			});

			modal.result.then(
				function(res) {
					$scope.machines.push(machine);
					$scope.values.machine = machine.id;
				},
				function() {}
			);
		}
	}

	$scope.customers = [
		{id:null, name:' --Select customer-- '},
	];
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
		var uri = process.id ? 
			process.links.getHref('edit') : 'process/json/add';
		$resource(uri).save($scope.values).$promise.then(
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

App.controller('_VersionModalCtrl', function($scope, $uibModalInstance, $uibModal, $resource, version) {
	$scope.stateOptions = [
		{id:MAVersion.STATE_IN_PROGRESS, 	name: MAVersion.stateLabel(MAVersion.STATE_IN_PROGRESS)},
		{id:MAVersion.STATE_APPROVED,  		name: MAVersion.stateLabel(MAVersion.STATE_APPROVED)},
		{id:MAVersion.STATE_CANCELLED, 		name: MAVersion.stateLabel(MAVersion.STATE_CANCELLED)},
	];
	$scope.version  	= version;
	$scope.values   	= JSON.parse(JSON.stringify(version));
	$scope.values.state	= $scope.values.state.toString();
	$scope.errors   	= {};
	$scope.init 		= function() {
		$scope.materials = [
			{id:null, name:" --Select Material-- "},
			{id:-1,   name:" --Create New-- "},
		];
		$scope.types 	 = [
			{id:null, name:" --Select Version type-- "},
			{id:-1,   name:" --Create New-- "},
		];
		$scope.parents	 = [{id:null, name:" --None-- "}].concat(version.process.versions);
		console.log($scope.parents, version);
		if ($scope.parents.indexOf(version) != -1) {
			$scope.parents.splice($scope.parents.indexOf(version), 1);
		}
		$resource('/process/material/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.materials.push(MAMaterial.fromJSON(item));
				});
			}, 
			function(err){}
		);
		$resource('/process/version/type/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.types.push(MAVersionType.fromJSON(item));
				});
			}, 
			function(err){}
		);
		console.log(version, $scope.values);
	}
	$scope.createMaterial = function() {
	 	if ($scope.values.material < 0) {	
			let material = new MAMaterial();
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/material-form.html',
				controller: '_MaterialModalCtrl',	
				scope: $scope,
				size: 'lg',
				resolve: {material: material},
			});

			modal.result.then(
				function(res) {
					$scope.materials.push(material);
					$scope.values.material = material.id;
				},
				function() {}
			);
		}
	}
	$scope.createType = function() {
	 	if ($scope.values.type < 0) {	
			let type = new MAVersionType();
			var modal = $uibModal.open({
				templateUrl : '/js/custom/tpl/modal/version-type-form.html',
				controller: '_VersionTypeModalCtrl',	
				scope: $scope,
				size: 'lg',
				resolve: {type: type},
			});

			modal.result.then(
				function(res) {
					$scope.types.push(type);
					$scope.values.type = type.id;
				},
				function() {}
			);
		}
	}
	$scope.save = function() {
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
					$scope.materials.push(MAMaterial.fromJSON(item));
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

    $scope.addImage = function(stage) {
        $scope.values.images.push({});
    }

	$scope.removeImage = function(i) {
        $scope.values.images.splice(i, 1);
	}

    var imagePreview = function(img, index) {
        $scope.values.images[index] = JSON.parse(JSON.stringify(img));
    }

    $scope.uploadFile = function(ev, entity, index) {
        var data = new FormData;
        var file = ev.target.files[0];
        var resource = $resource('/process/stage/image/json', {}, {
            upload: {
                method: 'POST',
                transformRequest: function(data) {
                    if (data === undefined)
                      return data;
                    var fd = new FormData();
                    angular.forEach(data, function(value, key) {
                      if (value instanceof FileList) {
                        if (value.length == 1) fd.append(key, value[0]);
                        else {
                          angular.forEach(value, function(file, index) {
                            fd.append(key + '_' + index, file);
                          });
                        }
                      } else fd.append(key, value);
                    });
                    return fd;
                },
                headers: {'Content-Type' : undefined}
            }});
        resource.upload({'file': file}).$promise.then(
             function(data) {
			    imagePreview(new MAImage(data), index);
             },
             function(err) {
				//entity.errors = err.data.errors;
       		 }
        );
    }

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
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
});

App.controller('_SimulationModalCtrl', function($scope, $uibModalInstance, $resource, simulation)
{
	$scope.simulation = simulation;
	$scope.values     = JSON.parse(JSON.stringify(simulation));
	$scope.errors     = {};

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
});

App.controller('_PlantModalCtrl', function($scope, $uibModalInstance, $resource, plant)
{
	$scope.plant 	= plant;
	$scope.values   = JSON.parse(JSON.stringify(plant));
	$scope.errors   = {};

	$scope.save = function() {
		var uri  = plant.id ? plant.links.getHref('edit') : '/process/plant/json/add';
		$resource(uri).save($scope.values).$promise.then(
			function(data) {
				plant.load(data);
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

App.controller('_MachineModalCtrl', function($scope, $uibModalInstance, $resource, machine)
{
	$scope.machine 	= machine;
	$scope.values   = JSON.parse(JSON.stringify(machine));
	$scope.errors   = {};

	$scope.save = function() {
		var uri  = machine.id ? machine.links.getHref('edit') : '/process/machine/json/add';
		$resource(uri).save($scope.values).$promise.then(
			function(data) {
				machine.load(data);
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

App.controller('_VersionTypeModalCtrl', function($scope, $uibModalInstance, $resource, type)
{
	$scope.type 	= type;
	$scope.values   = JSON.parse(JSON.stringify(type));
	$scope.errors   = {};

	$scope.save = function() {
		var uri  = type.id ? type.links.getHref('edit') : '/process/version/type/json/add';
		$resource(uri).save($scope.values).$promise.then(
			function(data) {
				type.load(data);
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

App.controller('_MaterialModalCtrl', function($scope, $uibModalInstance, $resource, material)
{
	$scope.material	= material;
	$scope.values   = JSON.parse(JSON.stringify(material));
	$scope.errors   = {};

	$scope.save = function() {
		var uri  = material.id ? material.links.getHref('edit') : '/process/material/json/add';
		$scope.values.text = $scope.values.name;
		$resource(uri).save($scope.values).$promise.then(
			function(data) {
				material.load(data);
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
