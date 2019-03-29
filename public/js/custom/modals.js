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

App.controller('_HintReasonRelationModalCtrl', function($scope, $uibModalInstance, $resource, relation) {
	$scope.relation 	  	 = relation;
	$scope.values  	  		 = JSON.parse(JSON.stringify(relation));
	console.log(relation, $scope.values);
	$scope.errors			 = {};
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	let hint			     = relation.reason.hint;
	let stage 		  	     = hint.stage;
	$scope.dflt    	  	     = dflt;
	$scope.prevStages 		 = $scope.version.stages.filter(s => {return s.order < stage.order});

	$scope.loadPreviousHints = function(value) {
		var previouses = [dflt];
		if (value.relation.reason.stage) {
			let prev = $scope.prevStages.find(e => {return e.id == value.relation.reason.stage});
			$resource(prev.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items.map(e => {return MAHint.fromJSON(e)}), (e,i) => {
						previouses.push(e);
					});
				},
				function(err) {},
			);
		}
		value.previouses = previouses;
	}

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

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_HintInfluenceRelationModalCtrl', function($scope,$uibModalInstance,$resource,relation) {
	$scope.relation 	  	 = relation;
	$scope.values  	  		 = JSON.parse(JSON.stringify(relation));
	console.log(relation, $scope.values);
	$scope.errors			 = {};
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	let hint			     = relation.influence.reason.hint;
	let stage 		  	     = hint.stage;
	$scope.dflt    	  	     = dflt;
	$scope.nextStages 		 = $scope.version.stages.filter(s => {return s.order > stage.order});
	$scope.loadNextHints = function(value) {
		var nexts = [dflt];
		if (value.source.stage) {
			let next= $scope.nextStages.find(e => {return e.id == value.source.stage});
			$resource(next.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items.map(e => {return MAHint.fromJSON(e)}), e => {
						nexts.push(e);
					});
				},
				function(err) {},
			);
		}
		value.nexts = nexts;
	}

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

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
});

App.controller('_HintReasonModalCtrl', function($scope, $uibModalInstance, $uibModal, $resource, reason) {
	$scope.reason 	  		 = reason;
	$scope.values  	  		 = JSON.parse(JSON.stringify(reason));
	$scope.errors			 = {influences:[]};
	//angular.forEach($scope.values.influences, (e, i) => {$scope.errors.influences[i] = {}});
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	var crto       	  	     = {id:-1,   name:" --Create new-- "};
	let hint			     = reason.hint;
	let stage 		  	     = hint.stage;
	$scope.dflt    	  	     = dflt;
	$scope.prevStages 		 = $scope.version.stages.filter(s => {return s.order < stage.order});
	$scope.nextStages 	     = $scope.version.stages.filter(s => {return s.order > stage.order});

	$scope.addInfluence = function(values) { 
		values.influences.push({notes:[], relations:[], simulations:[]});
		$scope.errors.influences.push({});
	}
	$scope.rmInfluence = function(values) {
		var index;
		if (-1 !== (index = $scope.values.influences.indexOf(values))) {
			$scope.values.influences.splice(index, 1);
			$scope.errors.influences.splice(index, 1);
		}
	}
	$scope.addReasonNote    = function(values) { values.notes.push({}) }
	$scope.addInfluenceNote = function(values) { values.notes.push({}) }
	$scope.addInfluenceRel  = function(values) {
		values.relations.push({source: {stage:null, hint:null}, relation: {}})
	}
	$scope.addReasonRel  = function(values) {
		values.relations.push({relation: {reason:{stage:null, hint:null}}, source: {}})
	}
	$scope.addSimulation = function(values) { values.simulations.push({suggestions: []}) }
	$scope.addSuggestion = function(values) { values.suggestions.push({}) }

	$scope.loadPreviousHints = function(value) {
		value.previouses = [dflt, crto];
		if (value.relation.reason.stage) {
			let prev = $scope.prevStages.find(e => {return e.id == value.relation.reason.stage});
			$resource(prev.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items.map(e => {return MAHint.fromJSON(e)}), (e,i) => {
						value.previouses.push(e);
					});
				},
				function(err) {},
			);
		}
	}

	$scope.loadNextHints = function(value) {
		value.nexts = [dflt, crto];
		if (value.source.stage) {
			let next= $scope.nextStages.find(e => {return e.id == value.source.stage});
			$resource(next.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items.map(e => {return MAHint.fromJSON(e)}), e => {
						value.nexts.push(e);
					});
				},
				function(err) {},
			);
		}
	}

	$scope.createPreviousHint = function(value) {
	 	if (value.relation.reason.hint < 0) {	
			let hint = new MAHint();
			hint.stage = $scope.prevStages.find(e => {return e.id == value.relation.reason.stage});
			var modal = $uibModal.open({
				animation: true,
				templateUrl : '/js/custom/tpl/modal/hint-form.html',
				controller: '_HintModalCtrl',	
				scope: $scope,
				size: 'lg',
				resolve: {hint: hint, stage: hint.stage}
			});

			modal.result.then(
				function(res) {
					value.previouses.push(hint);
					value.relation.reason.hint = hint.id;
					$scope.addSuccess("Saved succesfully");
				},
				function(err) {}
			);
		}
	}

	$scope.createNextHint = function(value) {
	 	if (value.source.hint < 0) {	
			let hint = new MAHint();
			hint.stage = $scope.nextStages.find(e => {return e.id == value.source.stage});
			var modal = $uibModal.open({
				animation: true,
				templateUrl : '/js/custom/tpl/modal/hint-form.html',
				controller: '_HintModalCtrl',	
				scope: $scope,
				size: 'lg',
				resolve: {hint: hint, stage: hint.stage}
			});

			modal.result.then(
				function(res) {
					value.nexts.push(hint);
					value.source.hint = hint.id;
					$scope.addSuccess("Saved succesfully");
				},
				function(err) {}
			);
		}
	}

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

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
	};
	console.log(reason, $scope.values);
});

App.controller('_HintInfluenceModalCtrl', function($scope, $uibModalInstance, $resource, influence) {
	$scope.influence  	     = influence;
	$scope.values  	  	     = JSON.parse(JSON.stringify(influence));
	$scope.errors		     = {};
	var dflt       	  	     = {id:null, name:" --Select one-- "};
	let hint			     = influence.reason.hint;
	let stage 		  	     = hint.stage;
	$scope.dflt    	  	     = dflt;
	$scope.nexts 		     = [];
	$scope.nextsLoading      = [];
	$scope.nextStages 	     = $scope.version.stages.filter(s => {return s.order > stage.order});
	$scope.addSimulation 	 = function(values) { values.simulations.push({suggestions: [{}]}) }
	$scope.addSuggestion 	 = function(values) { values.suggestions.push({}) }
	$scope.addInfluenceNote  = function(value) { 
		value.notes.push({});
	}
	$scope.addInfluenceRel   = function(value) {
		value.relations.push({source: {stage:null, hint:null}, relation: {}});
	}
	$scope.loadNextHints = function(value) {
		var nexts = [dflt];
		if (value.source.stage) {
			let next= $scope.nextStages.find(e => {return e.id == value.source.stage});
			$resource(next.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items.map(e => {return MAHint.fromJSON(e)}), e => {
						nexts.push(e);
					});
				},
				function(err) {},
			);
		}
		value.nexts = nexts;
	}

	$scope.save = function() {
		console.log($scope.values);
		var uri = influence.id ? influence.links.getHref('edit') : influence.reason.links.getHref('influence');
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

	$scope.cancel = function() {
		$uibModalInstance.dismiss('cancel');	
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

    $scope.addImage = function(stage) {
        $scope.values.images.push({});
    }

	$scope.removeImage = function(i) {
        $scope.values.images.splice(i, 1);
	}

    var imagePreview = function(img, index) {
        $scope.values.images[index] = JSON.parse(JSON.stringify(img));
    }

    $scope.uploadFile = function(ev, stage, index) {
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
				stage.errors = err.data.errors;
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
