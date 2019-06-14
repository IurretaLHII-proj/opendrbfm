//import * as MA from '../models/comment.model';

var _noneOption    = {id:null, name:'--- None ---'};
var _createOption  = {id:-1,   name:'--- Create new ---'};
var _defaultOption = {id:null, name:'--- Select one ---'};
var _stateOptions = [
{id:MASimulation.STATE_NOT_NECESSARY, name: MASimulation.stateLabel(MASimulation.STATE_NOT_NECESSARY)},
{id:MASimulation.STATE_NOT_PROCESSED, name: MASimulation.stateLabel(MASimulation.STATE_NOT_PROCESSED)},
{id:MASimulation.STATE_IN_PROGRESS, name: MASimulation.stateLabel(MASimulation.STATE_IN_PROGRESS)},
{id:MASimulation.STATE_FINISHED, name: MASimulation.stateLabel(MASimulation.STATE_FINISHED)},
{id:MASimulation.STATE_CANCELLED, name: MASimulation.stateLabel(MASimulation.STATE_CANCELLED)},
];

App.controller('_DetailCtrl', function($scope, $resource, $uibModal, $timeout) {
	$scope.locked  = true;
	$scope.version = null;
	$scope.current = null;
	$scope.setVersion = function(version) {
		$scope.version = version;
		$scope.current = null;
		if (!version.isStagesLoaded()) {
			$resource(version.links.getHref('stages')).get().$promise.then(
				function(data) {
					angular.forEach(data._embedded.items, e => {version.addStage(MAStage.fromJSON(e))});
					version.stagesLoaded = true;
					if (version.hasStages()) {
						let curr;
						if ($scope.params.stage) {
							curr = version.stages.find(e => {return e.id == $scope.params.stage}); 
							if (curr == undefined) curr = version.getActive();
						}
						else {
							curr = version.getActive(); 
						}
						$scope.setCurrent(curr);
					}
				},		
				function(err) {
					$scope.addError("Unable toobtain version stages");
				}		
			);
		}
		else if (version.hasStages()) $scope.setCurrent(version.getActive());
	}

	$scope.setCurrent = function(item) {
		let stage = $scope.version.stages.find(e => {return e.id == item.id});
		$resource(stage.links.getHref('hints')).get().$promise.then(
			function(data) {
				stage.hints = [];
				stage.addHints(data._embedded.items.map(e => {return MAHint.fromJSON(e)}));
				$scope.current = stage;
			},		
			function(err) {
				$scope.addError("Unable to obtain stage errors");
			}		
		);
	}

	$scope.init = function(item, values) {
		$scope.process = MAProcess.fromJSON(item);
		if ($scope.process.hasVersions()) {
			if ($scope.params.version) {
				$scope.setVersion($scope.process.versions.find(e => {return e.id == $scope.params.version}));
			}
			else {
				$scope.setVersion($scope.process.getActive());
			}
		}
		if (values) {
		//	angular.merge($scope.values, values);
		}
		console.log($scope.process);
	}

	$scope.isClosed = function(item) {
		if (item instanceof MAHintReason) {
			return item.closed;
		}
		else if (item instanceof MAHintInfluence) {
			return item.closed || $scope.isClosed(item.reason);
		}	
		else if (item instanceof MASimulation) {
			return item.closed || $scope.isClosed(item.influence);
		}	
		return false;
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
				$scope.process.reloadVersions();
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
				version.process.reloadVersions();
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
					$scope.process.reloadVersions();
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
			function(values) {
				if (values.standard) {
					let uri = stage.links.getHref('hint');
					stage.operations.forEach(op => {
						$resource(op.links.getHref('hints')).get({standard:true}).$promise.then(
							function(data) {
								angular.forEach(data._embedded.items, item => {
									let type = MAHintType.fromJSON(item);
									let values = {type: type.id, priority: type.priority};
									$resource(uri).save(values).$promise.then(
										function(data) { 
											let hint = MAHint.fromJSON(data);
											stage.addHint(hint);
											$scope.addSuccess("'"+hint.name+"' error loaded");
										},
										function(err) {
											$scope.addError(err.data.title);
										}	
									);
								});
							});
					
					});
				}
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

	/* FIXME!!! */
	/* Common functions to relation dialogs */
	var extended = {
		stateOptions: _stateOptions,
		loadNextHints: function(value) {
			value.nexts = [{id:null, name:" --Select one-- "}, {id:-1, name:" --Create new-- "}];
			if (value.source.stage) {
				let next= this.nextStages.find(e => {return e.id == value.source.stage});
				$resource(next.links.getHref('hints')).get().$promise.then(
					function(data) {
						angular.forEach(data._embedded.items.map(e => {return MAHint.fromJSON(e)}), e => {
							value.nexts.push(e);
						});
					},
					function(err) {},
				);
			}
		},
		createNextHint: function(value) {
			console.log(value);
		 	if (value.source.hint < 0) {	
				let hint = new MAHint();
				hint.stage = this.nextStages.find(e => {return e.id == value.source.stage});
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
		},
		loadPreviousHints: function(value) {
			value.previouses = [{id:null, name:" --Select one-- "}, {id:-1, name:" --Create new-- "}];
			if (value.relation.reason.stage) {
				let prev = this.prevStages.find(e => {return e.id == value.relation.reason.stage});
				$resource(prev.links.getHref('hints')).get().$promise.then(
					function(data) {
						angular.forEach(data._embedded.items.map(e => {return MAHint.fromJSON(e)}), e => {
							value.previouses.push(e);
						});
					},
					function(err) {},
				);
			}
		},
		createPreviousHint: function(value) {
		 	if (value.relation.reason.hint < 0) {	
				let hint = new MAHint();
				hint.stage = this.prevStages.find(e => {return e.id == value.relation.reason.stage});
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
		},
		addReasonNote: function(values) { values.notes.push({}) },
		addInfluenceNote: function(values) { values.notes.push({}) },
		addInfluenceRel: function(values) {
			values.relations.push({source: {stage:null, hint:null}, relation: {}})
		},
		addReasonRel: function(values) {
			values.relations.push({relation: {reason:{stage:null, hint:null}}, source: {}})
		},
		addSimulation: function(values) { values.simulations.push({
			state: MASimulation.STATE_NOT_NECESSARY.toString(),
			who: null,
			when: null,
			suggestions: [],
			effects: [],
			preventions: [],
		}) },
		addSuggestion: function(values) { values.suggestions.push({}) },
		addEffect: 	   function(values) { values.effects.push({}) },
		addPrevention: function(values) { values.preventions.push({}) },
		addInfluence : function(values) { 
			values.influences.push({notes:[], relations:[], simulations:[]});
			this.errors.influences.push({});
		},
		/*rmInfluence: function(values) {
			var index;
			if (-1 !== (index = values.influences.indexOf(values))) {
				.values.influences.splice(index, 1);
				this.errors.influences.splice(index, 1);
			}
		},*/
		init: function(stage) {
			this.prevStages = this.version.stages.filter(s => {return s.order < stage.order});
			this.nextStages = this.version.stages.filter(s => {return s.order > stage.order});
		},
		cancel: function() {
			this.modal.dismiss('cancel');	
		},
		prevStages: [],
		nextStages: [],
	};
	$scope.addHintReason = function(hint) {
		let reason  = new MAHintReason();
		//reason.addNote(new MANote());
		reason.hint = hint;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-reason-form.html',
			controller: '_HintReasonModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {
				reason: reason,
				extended: extended,
			}
		});

		modal.result.then(
			function() {
				hint.addReason(reason);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);
	}

	$scope.addHintInfluence = function(reason) {
		let influence    = new MAHintInfluence();
		//influence.addNote(new MANote());
		influence.reason = reason;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-influence-form.html',
			controller: '_HintInfluenceModalCtrl',	
			size: 'lg',
			scope: $scope,
			resolve: {
				influence : influence,
				extended  : extended, 
			}
		});

		modal.result.then(
			function() {
				reason.addInfluence(influence);
				$scope.addSuccess("Saved succesfully");
			},
			function() {}
		);

	}

	$scope.deleteHintReason = function(r) {
		var war = $scope._addWarning("Deleting...");
		$resource(r.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		r.hint.reasons.splice(r.hint.reasons.indexOf(r), 1);
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

	$scope.deleteHintInfluence = function(i) {
		var war = $scope._addWarning("Deleting...");
		$resource(i.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		i.reason.influences.splice(i.reason.influences.indexOf(i), 1);
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

	$scope.addReasonRelation = function(reason) {
		let r       = new MAHintRelation(); 
		r.relation  = new MAHintInfluenceRel();
		r.reason	= reason;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-reason-rel-form.html',
			controller: '_HintReasonRelationModalCtrl',
			scope: $scope,
			size: 'lg',
			resolve: {relation : r, extended: extended,}
		});

		modal.result.then(
			function() {
				reason.addRelation(r);
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.editReasonRelation = function(r) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-reason-rel-form.html',
			controller: '_HintReasonRelationModalCtrl',
			scope: $scope,
			size: 'lg',
			resolve: {relation : r, extended: extended,}
		});

		modal.result.then(
			function() {
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.editInfluenceRelation = function(r) {
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-influence-rel-form.html',
			controller: '_HintInfluenceRelationModalCtrl',
			scope: $scope,
			size: 'lg',
			resolve: {relation : r, extended: extended,}
		});

		modal.result.then(
			function() {
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.addInfluenceRelation = function(infl) {
		let r       = new MAHintRelation(); 
		r.source    = new MAHintReasonRel();
		r.influence	= infl;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/hint-influence-rel-form.html',
			controller: '_HintInfluenceRelationModalCtrl',
			scope: $scope,
			size: 'lg',
			resolve: {relation : r, extended: extended,}
		});

		modal.result.then(
			function() {
				infl.addRelation(r);
				$scope.addSuccess("Saved succesfully");
			},
			function() {
			}
		);
	}

	$scope.deleteReasonRelation = function(r) {
		var war = $scope._addWarning("Deleting...");
		$resource(r.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		r.reason.relations.splice(r.reason.relations.indexOf(r), 1);
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

	$scope.deleteInfluenceRelation = function(r) {
		var war = $scope._addWarning("Deleting...");
		$resource(r.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		r.influence.relations.splice(r.influence.relations.indexOf(r), 1);
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

	$scope.addSimulation = function(influence) {
		let simulation = new MASimulation();
		simulation.influence = influence;
		var modal = $uibModal.open({
			animation: true,
			templateUrl : '/js/custom/tpl/modal/render-form.html',
			controller: '_RenderModalCtrl',	
			size: 'lg',
			resolve: {simulation : simulation}
		});

		modal.result.then(
			function() {
				influence.addSimulation(simulation);
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
        		s.influence.simulations.splice(s.influence.simulations.indexOf(s), 1);
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
			controller: '_CommentDetailCtrl',	
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
			resolve: {note : item, link: s.links.keys['note']}
		});

		modal.result.then(
			function(res) {
				item.source.addNote(item);
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
			resolve: {note : item, link: s.links.keys['note']}
		});

		modal.result.then(
			function(res) {
				item.source.addNote(item);
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
		console.log(item);
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

	$scope.locked = true;
	$scope.stickyLock = function() {
		$scope.locked = !$scope.locked;
	}
});

App.controller('_CollectionCtrl', function($scope, $uibModal, $resource) {

	const classes = {
		"EMAHint"	: EMAHint,
		"MAProcess"	: MAProcess,
		"MAAction" 	: MAAction,
	}

	$scope.collection = new MACollection();

	$scope.init = function(collection, classNm) {
		$scope.classNm = classNm;
		collection._embedded.items = collection._embedded.items.map(e => {return classes[$scope.classNm].fromJSON(e)});
		$scope.collection.load(collection);
		console.log(collection, $scope.collection);
	};

	$scope.more = function() {
		$resource($scope.collection.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return classes[$scope.classNm].fromJSON(e)});
				$scope.collection.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}
});
