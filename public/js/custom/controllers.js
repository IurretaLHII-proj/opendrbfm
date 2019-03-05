//import * as MA from '../models/comment.model';

var _noneOption    = {id:null, name:'--- None ---'};
var _createOption  = {id:-1,   name:'--- Create new ---'};
var _defaultOption = {id:null, name:'--- Select one ---'};
var _stateOptions = [
		{id:MASimulation.NOT_PROCESSED,  name: "NOT PROCESSED"},
		{id:MASimulation.IN_PROGRESS,  name: "IN PROGRESS"},
		{id:MASimulation.FINISHED,  name: "FINISHED"},
		{id:MASimulation.NOT_NECESSARY, name: "NOT NECESSARY"},
		{id:MASimulation.CANCELlED, name: "CANCELlED"},
	];

App.controller('_DetailCtrl', function($scope, $resource, $uibModal, $timeout, api) {
	$scope.version = null;
	$scope.current = null;

	$scope.loadVersion = function(stage, items) {
		while(items.length) {
			let child = MAStage.fromJSON(items.shift(items));
			stage.addChild(child);
			$scope.loadVersion(child, items);
		}
	}

	$scope.setVersion = function(stage) {
		if (!stage.isChildrenLoaded()) {
			$resource(stage.links.getHref('children')).get().$promise.then(
				function(data) {
					stage.childrenLoaded = true;
					$scope.version = stage;
					$scope.loadVersion(stage, data._embedded.items);
					$scope.setCurrent(stage);
				},		
				function(err) {
				}		
			);
		}
		else {
			$scope.version = stage;
			$scope.setCurrent(stage);
		}
	}

	$scope.setCurrent = function(stage) {
		if (!stage.isHintsLoaded()) {
			$resource(stage.links.getHref('hints')).get().$promise.then(
				function(data) {
					stage.hintsLoaded = true;
					angular.forEach(data._embedded.items, e => {stage.addHint(MAHint.fromJSON(e))});
					$scope.current = stage;
				},		
				function(err) {
				}		
			);
		}
		else {
			$scope.current = stage;
		}
	}

	$scope.init = function(item, values) {
		if (item) {
			let process    = MAProcess.fromJSON(item);
			$scope.process = process;
			if (process.versions.length) {
				$scope.setVersion(process.versions[process.versions.length-1]);
			}
			console.log(process);
		}
		if (values) {
			//angular.merge($scope.values, values);
		}
	}

	$scope.deleteVersion = function(version) {
		var war = $scope._addWarning("Deleting version..");
		$resource(version._embedded._links.delete.href).delete().$promise.then(
			function (data) {
				$scope._closeWarning(war);
				$scope.addSuccess("Deleted succesfully");
				var i = $scope.values.versions.indexOf(version);
				$scope.values.versions.splice(i, 1);
				if ($scope.values.versions.length) {
					$scope.setVersion($scope.values.versions[$scope.values.versions.length-1]);
				}
				else {
					$scope.version = null;
					$scope.current = null;
				}
			},
			function (err) {
				//console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.cloneVersion = function(version) {
		var war = $scope._addWarning("Cloning version..");
		$resource(version._embedded._links.clone.href).get().$promise.then(
			function (data) {
				$scope._closeWarning(war);
				$scope.addSuccess("Saved succesfully");
				_stage = _prepareStage(data);
				$scope.values.versions.push(_stage);
				$scope.setVersion(_stage);
			},
			function (err) {
				//console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}

	/** Stage **/
	/*$scope.addStage = function(parentStage) {
		var _stage = {
			version: $scope.values.versions.length+1,
			level: 1,
			hints:[],
			images:[],
			children:[],
			process: $scope.values,
			parent: _noneOption,
			material:_defaultOption,
			operations:[_defaultOption],
			_links: {image: {href: '/process/stage/image/json'}}
		};
		_stage.getName = function() {
			var i=0;
			var that = this;
			while (that.parent.id) {
				i++;
				that = that.parent;
			}
			return 'Stage ' + i;
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
			scope: $scope,
			resolve: {stage: _stage, process: $scope.values,}
		});

		modal.result.then(
			function(res) {},
			function(err) {}
		);
	}*/
	$scope.addStage = function(pr) {
		let stage = new MAStage;
		if (pr) stage.parent = pr;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/stage-form.html',
			controller: '_StageModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {stage: stage}
		});

		modal.result.then(
			function(res) {
				//if (pr) pr.addChild(stage);
				//else $scope.process.addVersion(stage);
			},
			function(err) {}
		);
	}

	$scope.editStage = function(stage) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/stage-form.html',
			controller: '_StageModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {stage: stage}
		});

		modal.result.then(
			function(res) {
				console.log(stage);
				$resource('/process/:id/json').get({id:stage.process.id}).$promise.then(
					function(res) {
						$scope.init(res);
					},
					function(err) {
					}
					);
			},
			function(err) {}
		);
	}

	$scope.deleteStage = function(stage) {
		if (stage.parent.id) {
			stage.parent.children = stage.children;
			angular.forEach(stage.parent.children, function(item) { item.parent = stage.parent; });
			if (!stage.parent.parent.id) 
				$scope.setVersion(stage.parent);
			else
				$scope.setCurrent(stage.parent);
		}
		else if (stage.children.length) {
			stage.children[0].parent = _noneOption;
			stage.process.versions[stage.process.versions.indexOf(stage)] = stage.children[0];
			$scope.setVersion(stage.children[0]);
		}
		else {
		//	deleteVersion(stage);
		}
	}

	/* ------------------------------------------------------- */

	$scope.editProcess = function(process) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/process-form.html',
			controller: '_ProcessModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {process: process,}
		});

		modal.result.then(
			function(res) {
				if (process.versions.length) {
					$scope.setVersion(process.versions[process.versions.length-1]);
				}
			},
			function(err) {}
		);
	}

	/** Hints **/
	$scope.addHint = function() {
		let hint = new MAHint();
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-form.html',
			controller: '_HintModalCtrl',	
			size: 'lg',
			resolve: {hint: hint, stage: $scope.current}
		});

		modal.result.then(
			function(res) {$scope.current.hints.push(hint);},
			function(err) {}
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
			function(res) {},
			function(err) {}
		);
	}

	$scope.deleteHint = function(hint) {
		$resource(hint.links.getHref('delete')).delete().$promise.then(
			function (data) {
				$scope.addSuccess("Succesfully deleted");
        		$scope.current.hints.splice($scope.current.hints.indexOf(hint), 1);
			},
			function (err) {
				console.log(err);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.addSimulation = function(hint) {
		let simulation = new MASimulation();
		simulation.setHint(hint);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/simulation-form.html',
			controller: '_SimulationModalCtrl',	
			size: 'lg',
			resolve: {simulation : simulation}
		});

		modal.result.then(
			function() {
				hint.addSimulation(simulation);
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.editSimulation = function(simulation) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/simulation-form.html',
			controller: '_SimulationModalCtrl',	
			size: 'lg',
			resolve: {simulation : simulation}
		});

		modal.result.then(
			function() {
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.deleteSimulation = function(sm) {
		api.simulation.delete(sm).then(
			function (data) {
				$scope.addSuccess("Succesfully deleted");
        		sm.hint.simulations.splice(sm.hint.simulations.indexOf(sm), 1);
			},
			function (err) {
				console.log(err);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.renderSimulation = function(simulation) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/render-form.html',
			controller: '_RenderModalCtrl',	
			size: 'lg',
			resolve: {simulation : simulation}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.addComment = function(item) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/comment-form.html',
			controller: 'CommentCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {item : item}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.addReason = function(s) {
		let item  = new MANote;
		item.setSimulation(s);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/note-form.html',
			controller: '_NoteModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {note : item, link: s.links.keys['reason']}
		});

		modal.result.then(
			function(res) {
				item.simulation.reasons.push(item);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.addInfluence = function(s) {
		let item  = new MANote;
		item.setSimulation(s);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/note-form.html',
			controller: '_NoteModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {note : item, link: s.links.keys['influence']}
		});

		modal.result.then(
			function(res) {
				item.simulation.influences.push(item);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.addSuggestion = function(s) {
		let item  = new MANote;
		item.setSimulation(s);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/note-form.html',
			controller: '_NoteModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {note : item, link: s.links.keys['suggestion']}
		});

		modal.result.then(
			function(res) {
				item.simulation.suggestions.push(item);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.editNote = function(item) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/note-form.html',
			controller: '_NoteModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {note : item, link: {}}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.deleteNote = function(item) {
		$resource(item.links.getHref('delete')).delete().$promise.then(
			function (data) {
				item.simulation.removeNote(item);
				$scope.addSuccess("Removed succesfully");
			},
			function (err) {
				console.log(err);
				$scope.addError(err.data.title);
			}	
		);
	}
});

App.controller('_OperationTypesCtrl', function($scope, $resource, $uibModal) {
	$scope.collection = [];
	$scope.init = function(collection) {
		angular.forEach(collection._embedded.items, function(item, index) {
			let group = MAOperationType.fromJSON(item);
			$scope.collection.push(group); 
		});
	};

	$scope.editGroup = function(group) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-type-form.html',
			controller: '_OperationTypeModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {group: group}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.addOperation = function(type) {
		let op = new MAOperation;
		op.type = type;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {op:op}
		});

		modal.result.then(
			function(res) {
				type.addOperation(op);
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.editOperation = function(op) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {op:op}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.showHints = function(op) {
		if (!op._sh) {
			op.hints = [];
			$resource(op.links.getHref('hints')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items, item => {
						op.addHint(MAHintType.fromJSON(item));
					});
					op._sh=true;
				});
		}
		op._sh = !op._sh;
	};

	$scope.addHint = function(op) {
		let type = new MAHintType();
		type.operation = op;
		var modal = $uibModal.open({
			templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
			controller: '_HintTypeModalCtrl',	
			size: 'lg',
			resolve: {type: type},
		});

		modal.result.then(
			function(res) {
				op.addHint(type);
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.editHint = function(hint) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-type-form.html',
			controller: '_HintTypeModalCtrl',	
			size: 'lg',
			resolve: {type: hint} 
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
});

App.controller('__OperationTypesCtrl', function($scope, $resource, $uibModal, api) {
		
	$scope.values = [];
	$scope.init = function(collection) {
		$scope.collection = collection._embedded.items;
		angular.forEach($scope.collection, function(item, index) {
			var group = angular.copy(item);
			group._embedded = item;
			group.operations = [];
			angular.forEach(item._embedded.operations, function(op, i) {
				group.operations[i] = angular.copy(op);
				group.operations[i]._embedded = op;
				group.operations[i].type = group;
				group.operations[i].children = [];
				angular.forEach(op._embedded.children, function(ch) {
					group.operations[i].children.push(angular.copy(ch));
				});
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
			scope: $scope,
			resolve: {group: group}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
	$scope.deleteGroup = function(group) {
		var war = $scope._addWarning("Deleting...");
		$resource(group._embedded._links.delete.href).delete().$promise.then(
			function (data) {
				$scope._closeWarning(war);
        		$scope.values.splice($scope.values.indexOf(group), 1);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}
	$scope.addOperation = function(group) {
		var op = {type: group, children: []};
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {group: group, op:op}
		});

		modal.result.then(
			function(res) {
				group.operations.push(op);
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
	$scope.editOperation = function(group, op) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/operation-form.html',
			controller: '_OperationModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {group: group, op:op}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
	$scope.deleteOperation = function(group, op) {
		var war = $scope._addWarning("Deleting...");
		api.operation.delete(op._embedded).then(
			function (data) {
				$scope._closeWarning(war);
        		group.operations.splice(group.operations.indexOf(op), 1);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				$scope._closeWarning(war);
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
			function(err) {}
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
			function(err) {}
		);
	}
});
