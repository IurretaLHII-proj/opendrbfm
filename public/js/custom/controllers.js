//import * as MA from '../models/comment.model';

var _noneOption    = {id:null, name:'--- None ---'};
var _createOption  = {id:-1,   name:'--- Create new ---'};
var _defaultOption = {id:null, name:'--- Select one ---'};
var _stateOptions = [
		{id:0,  name: "NOT PROCESSED"},
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
	
	var _prepareHint = function(hint, stage) {
		_hint = angular.copy(hint);
		//_hint.operation   = hint._embedded.operation;
		if ((i = stage.operations.map(function(item) {return item.id}).indexOf(hint._embedded.operation.id)) >= 0){
			_hint.operation = stage.operations[i];
		}
		_hint._embedded   = hint;
		_hint.type 		  = hint._embedded.type;
		_hint.stage		  = stage;
		_hint.parents     = [];
		_hint.simulations = [];
		angular.forEach (hint._embedded.simulations, function(sm) {
			_sm = angular.copy(sm);
			_sm._embedded 	= sm;
			_sm.when	  	= sm.when ? new Date(sm.when*1000) : null,
			_sm.reasons     = [];
			_sm.suggestions = [];
			_sm.influences  = [];
			angular.forEach (sm._embedded.reasons, function(note, i) {
				_sm.reasons[i] = angular.copy(note);
				_sm.reasons[i]._embedded = note;
			});
			angular.forEach (sm._embedded.suggestions, function(note, i) {
				_sm.suggestions[i] = angular.copy(note);
				_sm.suggestions[i]._embedded = note;
			});
			angular.forEach (sm._embedded.influences, function(note, i) {
				_sm.influences[i] = angular.copy(note);
				_sm.influences[i]._embedded = note;
			});
			_sm.hint = _hint;
			_hint.simulations.push(_sm);
		});
		return _hint;
	}

	var _prepareStage = function(stage) {
		_stage = angular.copy(stage);
		_stage._embedded  = stage;
		_stage.material   = stage._embedded.material;
		_stage.parent     = _noneOption,
		_stage.operations = [];
		_stage.process    = $scope.values;
		angular.forEach(stage._embedded.operations, function(op, i) {
			_stage.operations[i] = _prepareOperation(op);
		});
		if (!_stage.operations.length) _stage.operations.push({});

		_stage.images = [];
		angular.forEach(stage._embedded.images, function(image, i) {
			_stage.images[i] = image;
		});	
		_stage.getName = function() {
			//return 'Stage' + this.level;
			var i=0;
			var that = this;
			while (that.parent.id) {
				i++;
				that = that.parent;
			}
			return 'Stage ' + i;
		};
		return _stage;
	}

	$scope.version = null;
	$scope.current = null;
	$scope.errors  = {};
	$scope.values  = {versions: []};

	$scope.init = function(item, values) {
		if (item) {
			console.log(item);
			$scope.values = angular.copy(item);
			$scope.values._embedded = item;
			$scope.values.versions  = [];
			if (item._embedded &&
				item._embedded.versions && 
				item._embedded.versions.length) {

				angular.forEach(item._embedded.versions, function(version, index) {
					_stage = _prepareStage(version);
					$scope.values.versions[index] = _stage;
				});

				if ($scope.current == null) {
					$scope.setVersion($scope.values.versions[$scope.values.versions.length-1]);
				}
			}
		}
		if (values) {
			angular.merge($scope.values, values);
		}
		console.log(item,$scope.values);
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

	$scope.setVersion = function(version) {
		console.log(version);
		$scope.version = version;
		if (typeof version.children == "undefined") {
			version.childrenLoading = true;
			$resource(version._embedded._links.children.href).get().$promise.then(
				function (data) {

				//$timeout(function() {
					version.childrenLoading = false;
					_initVersion(version, data._embedded.items);
					//$scope.setCurrent(version);
				//}, 10);
				},
				function (err) {
					console.log(err);
					$scope.addError(err.data.title);
				}	
			);
		}
		$scope.setCurrent(version);
	};

	$scope.setCurrent = function(stage) {
		$scope.current = stage;
		console.log(stage);
		if (typeof stage.hints === "undefined") {
			stage.hints = [];
			stage.hintsLoading = true;
			api.stage.getHints(stage._embedded).then(function(data) {
				//$timeout(function() {
				angular.forEach(data._embedded.items, function(hint, i) {
					stage.hints[i] = _prepareHint(hint, stage);
				});
				stage.hintsLoading = false;
				//}, 10);
			},);
		}
	};

	/** Stage **/
	$scope.addStage = function(parentStage) {
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
	}

	$scope.editStage = function(stage) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/stage-form.html',
			controller: '_StageModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {stage: stage, process: $scope.values,}
		});

		modal.result.then(
			function(res) {
				//if (stage.process.versions.indexOf(stage) != -1) $scope.setVersion(stage);
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

	/** Hints **/
	$scope.addHint = function() {
		var _hint = {};
		_hint.operation   = $scope.current.operations[0];
		_hint.stage		  = $scope.current;
		_hint.type		  = _defaultOption;
		_hint.simulations = [];
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-form.html',
			controller: '_HintModalCtrl',	
			size: 'lg',
			resolve: {hint: _hint, stage: $scope.current}
		});

		modal.result.then(
			function(res) {
				_hint.stage.hints.push(_hint);
			},
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
			function(res) {
			},
			function(err) {}
		);
	}

	$scope.deleteHint = function(h) {
		api.hint.delete(h._embedded).then(
			function (data) {
				$scope.addSuccess("Succesfully deleted");
        		h.stage.hints.splice(h.stage.hints.indexOf(h), 1);
			},
			function (err) {
				console.log(err);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.addSimulation = function(hint) {
		var _sim = {
			state		: 0,
			who			: null,
			when		: null,
			reasons     : [],
			influences  : [],
			suggestions : [],
			parents		: [],
			hint: hint,
		};
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/simulation-form.html',
			controller: '_SimulationModalCtrl',	
			size: 'lg',
			resolve: {simulation : _sim}
		});

		modal.result.then(
			function() {
				hint.simulations.push(_sim);
				angular.forEach(_sim.influences, function(i) {
					//if (i.stage.id) {
					//   	if (i.simulation.id == -1) {
					//		i.hint.simulations.push({
					//			parents:[_sim],
					//			reasons:[i],
					//			influences:[],
					//			suggestions:[],
					//		});
					//	}
					//	else {
					//		i.simulation.parents.push(_sim);
					//	}
					//}
				});
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.editSimulation = function(_sim) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/simulation-form.html',
			controller: '_SimulationModalCtrl',	
			size: 'lg',
			resolve: {simulation : _sim}
		});

		modal.result.then(
			function() {
				angular.forEach(_sim.influences, function(i) {
					//if (i.stage.id && i.simulation.id == -1) {
					//	i.hint.simulations.push({
					//		parents:[_sim],
					//		reasons:[i],
					//		influences:[],
					//		suggestions:[],
					//	});
					//}
				});
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.deleteSimulation = function(sm) {
		api.simulation.delete(sm._embedded).then(
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

	$scope.renderSimulation = function(_sim) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/render-form.html',
			controller: '_RenderModalCtrl',	
			size: 'lg',
			resolve: {simulation : _sim}
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
				console.log(res);
				$scope.addSuccess("Saved succesfully");
			},
			function() {
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
