App.controller('_HintCollectionCtrl', function($scope, $resource, $location) {

	$scope.order		= 'priority';
	$scope.criteria		= 'DESC';
	$scope.priority		= 0;
	$scope.operations 	= [{id:null, longName:"--- ANY ---"}];
	$scope.errors 		= [{id:null, name:"--- ANY ---"}];
	$scope.materials 	= [{id:null, name:"--- ANY ---"}];
	$scope.types 		= [{id:null, name:"--- ANY ---"}];
	$scope.states		= [
		{id:null, 					name: "--- ANY ---"},
		{id:MAVersion.STATE_IN_PROGRESS, 	name: MAVersion.stateLabel(MAVersion.STATE_IN_PROGRESS)},
		{id:MAVersion.STATE_APPROVED,  		name: MAVersion.stateLabel(MAVersion.STATE_APPROVED)},
		{id:MAVersion.STATE_CANCELLED, 		name: MAVersion.stateLabel(MAVersion.STATE_CANCELLED)},
	];
	$scope.state  	 = $scope.states[0];
	$scope.material  = $scope.materials[0];
	$scope.type 	 = $scope.types[0];
	$scope.operation = $scope.operations[0];
	$scope.error 	 = $scope.errors[0];
	$scope.getQuery		= function() {
		let query = {};
		if ($scope.operation.id) query.op 	    = $scope.operation.id;
		if ($scope.error.id) 	 query.hint 	= $scope.error.id;
		if ($scope.material.id)  query.material = $scope.material.id;
		if ($scope.type.id) 	 query.type 	= $scope.type.id;
		if ($scope.state.id) 	 query.state    = $scope.state.id;
		if ($scope.process) 	 query.process  = $scope.process.id;
		query.prior    = $scope.priority;
		query.order    = $scope.order;
		query.criteria = $scope.criteria;
		return query;
	}

	$scope.init = function() {
		$scope.collection = new MACollection();
		$resource('/process/material/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.materials.push(new MAMaterial(item));
				});
				$resource('/process/version/type/json').get().$promise.then(
					function(data){
						angular.forEach(data._embedded.items, item => {
							$scope.types.push(new MAVersionType(item));
						});
						$resource('/process/op/type/json').get().$promise.then(
							function(data){
								angular.forEach(data._embedded.items, item => {
									let type = MAOperationType.fromJSON(item);
									type.operations.forEach(e => {$scope.operations.push(e)});
								});
								if ($scope.params.op) {
									let selected = $scope.operations.find(e => {
										return e.id == $scope.params.op
									});
									selected.hints 	 = [];
									$scope.operation = selected; 
									$resource(selected.links.getHref('hints')).get().$promise.then(
										function(data) {
											angular.forEach(data._embedded.items, item => {
												selected.addHint(MAHintType.fromJSON(item));
											});
											if ($scope.params.hint) {
												let h = selected.hints.find(e=>{
													return e.id == $scope.params.hint
												});
												if (h) $scope.error = h;
											}
											$scope.search();
										}
									);
								}
								else $scope.search();
							}, 
							function(err){}
						);
					}, 
					function(err){}
				);
			}, 
			function(err){}
		);
	}

	$scope.selOrder		= function(value) { $scope.order	= value; }
	$scope.selCriteria	= function(value) { $scope.criteria	= value; }
	$scope.selType 		= function(value) { $scope.type		= value; }
	$scope.selMaterial	= function(value) { $scope.material = value; }
	$scope.selState		= function(value) { $scope.state 	= value; }
	$scope.selError		= function(value) { $scope.error 	= value; }
	$scope.selOp 		= function(value) { 
		$scope.error	= $scope.errors[0];
		$scope.operation= value; 
		if (value.id) {
			$scope.operation.hints = [];
			$resource($scope.operation.links.getHref('hints')).get({order:'priority', criteria:'desc'}).$promise.then(
				function(data) {
					angular.forEach(data._embedded.items, item => {
						$scope.operation.addHint(MAHintType.fromJSON(item));
					});
					if ($scope.params.hint) {
						let selected = $scope.operation.hints.find(e=>{return e.id == $scope.params.op});
						if (selected) $scope.selError(selected);
					}
				});
		}
	}

	$scope.search = function() {
		$scope.collection = new MACollection();
		$resource('/process/hint/json').get($scope.getQuery()).$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return EMAHint.fromJSON(e)});
				$scope.collection.load(data);
				console.log($scope.collection);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.more = function() {
		$resource($scope.collection.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return EMAHint.fromJSON(e)});
				$scope.collection.load(data);
				console.log($scope.collection);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.rmProcess = function() {$scope.process = null;}
	$scope.process = null;
	$scope.autoCompleteOptions = {
		minimumChars: 3,
		maxItemsToRender: 20,
		selectedTextAttr: 'title',
		itemTemplateUrl: 'autocomplete-item.html',
		noMatchTemplate: "<span>No results match</span>",
		data: function(text) {
			return $resource('/process/json').get({title: text, order:'title'}).$promise.then(
				function(data) {
					return data._embedded.items;
				},
				function(err) {
				}
			);
		},
		itemSelected: function (e) {
        	$scope.process = e.item;
        }
	};
});
