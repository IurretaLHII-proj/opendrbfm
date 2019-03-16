//import * as MA from '../models/comment.model';

var _noneOption    = {id:null, name:'--- None ---'};
var _createOption  = {id:-1,   name:'--- Create new ---'};
var _defaultOption = {id:null, name:'--- Select one ---'};
var _stateOptions = [
		{id:MASimulation.NOT_PROCESSED,  name: "NOT PROCESSED"},
		{id:MASimulation.IN_PROGRESS,  name: "IN PROGRESS"},
		{id:MASimulation.FINISHED,  name: "FINISHED"},
		{id:MASimulation.NOT_NECESSARY, name: "NOT NECESSARY"},
		{id:MASimulation.CANCELLED, name: "CANCELLED"},
	];

App.controller('_DetailCtrl', function($scope, $resource, $uibModal, $timeout) {
	$scope.version = null;
	$scope.current = null;
	$scope.commons = {
		stage: {
			getHints: function(stage, callback) {
				if (stage.isHintsLoaded()) {
					if (callback) callback();
				}
				else {
					stage.hintsLoaded = true;
					$resource(stage.links.getHref('hints')).get().$promise.then(
						function(data) {
							//$timeout(function(){
							stage.addHints(data._embedded.items.map(e => {return MAHint.fromJSON(e)}));
							//}, 1000);
							if (callback) callback();
						},		
						function(err) {
						}		
					);
				}
			}
		}
	};

	$scope.setVersion = function(version) {
		$scope.version = version;
		$scope.current = null;
		if (!version.isStagesLoaded()) {
			$resource(version.links.getHref('stages')).get().$promise.then(
				function(data) {
					//$timeout(function(){
					angular.forEach(data._embedded.items, e => {version.addStage(MAStage.fromJSON(e))});
					version.stagesLoaded = true;
					if (version.hasStages()) $scope.setCurrent(version.getActive());
					//}, 1000);
				},		
				function(err) {
				}		
			);
		}
		else if (version.hasStages()) $scope.setCurrent(version.getActive());
	}

	$scope.setCurrent = function(stage) {
		$scope.commons.stage.getHints(stage, function() {$scope.current = stage; console.log(stage)});
	}

	$scope.init = function(item, values) {
		let process    = MAProcess.fromJSON(item);
		$scope.process = process;
		if ($scope.process.hasVersions()) {
			$scope.setVersion($scope.process.getActive());
		}
		console.log(process);
		//if (values) {
		//	angular.merge($scope.values, values);
		//}
	}

	$scope.cloneVersion = function(version) {
		var war = $scope._addWarning("Cloning version..");
		$resource(version.links.getHref('clone')).get().$promise.then(
			function (data) {
				let active = MAVersion.fromJSON(data);
				$scope.process.addVersion(active);
				$scope.setVersion(active);
				$scope._closeWarning(war);
				$scope.addSuccess("Saved succesfully");
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.addVersion = function() {
		let version = new MAVersion;
		version.name = "Version " + ($scope.process.versions.length+1),
		version.process = $scope.process;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/version-form.html',
			controller: '_VersionModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {version: version}
		});

		modal.result.then(
			function(res) {
				$scope.process.addVersion(version);
				$scope.setVersion(version);
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.editVersion = function(version) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/version-form.html',
			controller: '_VersionModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {version: version}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.deleteVersion = function(version) {
		var war = $scope._addWarning("Deleting...");
		$resource(version.links.getHref('delete')).delete().$promise.then(
				function(data) {
					$scope.process.removeVersion(version);
					if ($scope.process.hasVersions()) {
						$scope.setVersion($scope.process.getActive());
					}
					else {
						$scope.version = null;
						$scope.current = null;
					}
					$scope._closeWarning(war);
					$scope.addSuccess("Succesfully deleted");
				},
				function(err) {
					$scope._closeWarning(war);
					$scope.addError(err.data.title);
				},
			);
	}

	$scope.stagesUpdated = function(version) {
		$scope.values  = JSON.parse(JSON.stringify(version));
		angular.forEach($scope.values.stages, function(e, i) { e.order = i; });
		var war = $scope._addWarning("Updating...");
		$resource(version.links.getHref('edit')).save($scope.values).$promise.then(
				function(data) {
					angular.forEach(version.stages, function(e, i) { e.order = i; });
					$scope._closeWarning(war);
					$scope.addSuccess("Saved succesfully");
				},
				function(err) {
					$scope._closeWarning(war);
					$scope.addError(err.data.title);
				},
			);
	}

	$scope.addStage = function(version) {
		let stage = new MAStage;
		stage.version = version;
		stage.order   = version.stages.length;
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
				version.addStage(stage);
				$scope.setCurrent(stage);
				$scope.addSuccess("Saved succesfully");
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
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.deleteStage = function(stage) {
		var war = $scope._addWarning("Deleting...");
		$resource(stage.links.getHref('delete')).delete().$promise.then(
				function(data) {
					stage.version.removeStage(stage);
					if (stage.version.hasStages()) $scope.setCurrent(stage.version.getActive());
					else $scope.current = null;
					$scope._closeWarning(war);
					$scope.addSuccess("Succesfully deleted");
				},
				function(err) {
					$scope._closeWarning(war);
					$scope.addError(err.data.title);
				},
			);
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
				if (process.hasVersions()) {
					$scope.setVersion(process.getActive());
				}
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	/** Hints **/
	$scope.addHint = function(stage) {
		let hint = new MAHint();
		hint.stage = stage;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-form.html',
			controller: '_HintModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {hint: hint, stage: $scope.current}
		});

		modal.result.then(
			function(res) {
				stage.addHint(hint);
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.editHint = function(hint) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-form.html',
			controller: '_HintModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {hint: hint, stage: $scope.current}
		});

		modal.result.then(
			function(res) {
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}

	$scope.deleteHint = function(hint) {
		var war = $scope._addWarning("Deleting...");
		$resource(hint.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		$scope.current.hints.splice($scope.current.hints.indexOf(hint), 1);
				$scope._closeWarning(war);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.addContext = function(hint) {
		let context = new MAHintContext();
		context.setHint(hint);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-context-form.html',
			controller: '_HintContextModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {e : context}
		});

		modal.result.then(
			function() {
				hint.addContext(context);
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.editContext = function(context) {
		let h = context.hint;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-context-form.html',
			controller: '_HintContextModalCtrl',	
			scope: $scope,
			size: 'lg',
			resolve: {e : context}
		});

		modal.result.then(
			function() {
				//fixme
				context.hint = h;
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.deleteContext = function(c) {
		var war = $scope._addWarning("Deleting...");
		$resource(c.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		c.hint.contexts.splice(c.hint.contexts.indexOf(c), 1);
				$scope._closeWarning(war);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
		);
	}

	$scope.addSimulation = function(context) {
		let simulation = new MASimulation();
		simulation.setContext(context);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/render-form.html',
			controller: '_RenderModalCtrl',	
			size: 'lg',
			resolve: {simulation : simulation}
		});

		modal.result.then(
			function() {
				context.addSimulation(simulation);
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.editSimulation = function(simulation) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/render-form.html',
			controller: '_RenderModalCtrl',	
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

	$scope.deleteSimulation = function(s) {
		var war = $scope._addWarning("Deleting...");
		$resource(s.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		s.context.simulations.splice(s.context.simulations.indexOf(s), 1);
				$scope._closeWarning(war);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
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
			resolve: {source : item}
		});

		modal.result.then(
			function(res) {
				item.addComment(comment);
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.addReason = function(s) {
		let item  = new MANote;
		item.setSource(s);
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
				item.source.reasons.push(item);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.addInfluence = function(s) {
		let item  = new MANote;
		item.setSource(s);
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
				item.source.influences.push(item);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.addSuggestion = function(s) {
		let item  = new MANote;
		item.setSource(s);
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
				item.source.suggestions.push(item);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.addEffect = function(s) {
		let item  = new MANote;
		item.setSource(s);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/note-form.html',
			controller: '_NoteModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {note : item, link: s.links.keys['effect']}
		});

		modal.result.then(
			function(res) {
				item.source.effects.push(item);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.addPrevention = function(s) {
		let item  = new MANote;
		item.setSource(s);
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/note-form.html',
			controller: '_NoteModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {note : item, link: s.links.keys['prevention']}
		});

		modal.result.then(
			function(res) {
				item.source.preventions.push(item);
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
		var war = $scope._addWarning("Deleting...");
		$resource(item.links.getHref('delete')).delete().$promise.then(
			function (data) {
				item.source.removeNote(item);
				$scope._closeWarning(war);
				$scope.addSuccess("Removed succesfully");
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
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

	$scope.deleteOperation = function(op) {
		var war = $scope._addWarning("Deleting...");
		$resource(op.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		op.type.removeOperation(op);
				$scope._closeWarning(war);
				$scope.addSuccess("Succesfully deleted");
			},
			function (err) {
				console.log(err);
				$scope._closeWarning(war);
				$scope.addError(err.data.title);
			}	
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
