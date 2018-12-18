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
