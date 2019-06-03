
App.controller('_ProcessCollectionCtrl', function($scope, $uibModal, $resource, $window, $location) {

	$scope.order		= 'created';
	$scope.criteria		= 'DESC';
	$scope.users		= [{id:null, name: " --ANY-- "}];
	$scope.customers	= [{id:null, name: " --ANY-- "}];
	$scope.complexityOptions = [
		{id:null,  	 					 name: " --ANY-- "},
		{id:MAProcess.COMPLEXITY_LOW,  	 name: MAProcess.complexityLabel(MAProcess.COMPLEXITY_LOW)},
		{id:MAProcess.COMPLEXITY_MEDIUM, name: MAProcess.complexityLabel(MAProcess.COMPLEXITY_MEDIUM)},
		{id:MAProcess.COMPLEXITY_HIGH,   name: MAProcess.complexityLabel(MAProcess.COMPLEXITY_HIGH)},
	];
	$scope.materials 	= [{id:null, name:"--- ANY ---"}];
	$scope.types 		= [{id:null, name:"--- ANY ---"}];
	$scope.states		= [
		{id:null,							name: "--- ANY ---"},
		{id:MAVersion.STATE_IN_PROGRESS, 	name: MAVersion.stateLabel(MAVersion.STATE_IN_PROGRESS)},
		{id:MAVersion.STATE_APPROVED,  		name: MAVersion.stateLabel(MAVersion.STATE_APPROVED)},
		{id:MAVersion.STATE_CANCELLED, 		name: MAVersion.stateLabel(MAVersion.STATE_CANCELLED)},
	];
	$scope.state  	  = $scope.states[0];
	$scope.plants	  = [
		{id:null, name: "--- ANY ---"},
	];
	$scope.plant  	  = $scope.plants[0];
	$scope.machines	  = [
		{id:null, name: "--- ANY ---"},
	];
	$scope.machine 	  = $scope.machines[0];
	$scope.material   = $scope.materials[0];
	$scope.type 	  = $scope.types[0];
	$scope.complexity = $scope.complexityOptions[0];
	$scope.owner	  = $scope.users[0];
	$scope.customer	  = $scope.customers[0];
	$scope.getQuery	  = function() {
		let query = {};
		if ($scope.complexity.id)	 query.complexity  = $scope.complexity.id;
		if ($scope.customer)		 query.customer	   = $scope.customer.id;
		if ($scope.owner)	 		 query.user 	   = $scope.owner.id;
		if ($scope.process)	 		 query.title 	   = $scope.process;
		if ($scope.article)	 		 query.article 	   = $scope.article;
		if ($scope.machine)	 		 query.machine 	   = $scope.machine.id;
		if ($scope.line)	 		 query.line 	   = $scope.line;
		if ($scope.piece)	 		 query.piece 	   = $scope.piece;
		if ($scope.material.id)  	 query.material    = $scope.material.id;
		if ($scope.type.id) 	 	 query.type 	   = $scope.type.id;
		if ($scope.state.id) 	 	 query.state       = $scope.state.id;
		if ($scope.plant.id) 	 	 query.plant       = $scope.plant.id;
		if ($scope.tpl)				 query.tpl		   = true;
		query.order    = $scope.order;
		query.criteria = $scope.criteria;
		//query.limit	   = 5;
		return query;
	}

	$scope.init = function(tpl) {
		$scope.tpl = (tpl == true);
		$scope.collection = new MACollection();
		$resource('/user/json').get().$promise.then(
			function(data){
				angular.forEach(data._embedded.items, item => {
					$scope.users.push(new MAUser(item));
				});
				$resource('/customer/json').get().$promise.then(
					function(data){
						angular.forEach(data._embedded.items, item => {
							$scope.customers.push(new MAUser(item));
						});
						$resource('/process/material/json').get().$promise.then(
							function(data){
								angular.forEach(data._embedded.items, item => {
									$scope.materials.push(MAMaterial.fromJSON(item));
								});
								$resource('/process/version/type/json').get().$promise.then(
									function(data){
										angular.forEach(data._embedded.items, item => {
											$scope.types.push(MAVersionType.fromJSON(item));
										});
										$resource('/process/plant/json').get().$promise.then(
											function(data){
												angular.forEach(data._embedded.items, item => {
													$scope.plants.push(MAPlant.fromJSON(item));
												});
												$resource('/process/machine/json').get().$promise.then(
													function(data){
														angular.forEach(data._embedded.items, item => {
															$scope.machines.push(MAMachine.fromJSON(item));
														});
														$scope.search();
													}, 
													function(err){}
												);
											}, 
											function(err){}
										);
									}, 
									function(err){}
								);
							}, 
							function(err){}
						);
					},
					function (err){}
				);
			},
			function (err) {}
		);	
	}

	$scope.selOrder		 = function(value) { $scope.order	   = value; }
	$scope.selCriteria	 = function(value) { $scope.criteria   = value; }
	$scope.selComplexity = function(value) { $scope.complexity = value; }
	$scope.selOwner 	 = function(value) { $scope.owner 	   = value; }
	$scope.selCustomer 	 = function(value) { $scope.customer   = value; }
	$scope.selType 		 = function(value) { $scope.type	   = value; }
	$scope.selMaterial	 = function(value) { $scope.material   = value; }
	$scope.selState		 = function(value) { $scope.state 	   = value; }
	$scope.selPlant		 = function(value) { $scope.plant 	   = value; }
	$scope.selMachine	 = function(value) { $scope.machine	   = value; }

	$scope.search = function() {
		$scope.collection = new MACollection();
		$resource('/process/json').get($scope.getQuery()).$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAProcess.fromJSON(e)});
				$scope.collection.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.more = function() {
		$resource($scope.collection.links.getHref('next')).get().$promise.then(
			function(data) {
				data._embedded.items = data._embedded.items.map(e => {return MAProcess.fromJSON(e)});
				$scope.collection.load(data);
			},		
			function(err) {
				$scope.errors = err;
			}
		);	
	}

	$scope.addProcess = function() {
		let process = new MAProcess();
		process.tpl = $scope.tpl;
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
				$scope.addSuccess("Saved succesfully");
				$window.location.href = process.links.getHref();
			},
			function(err) {}
		);
	}
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
				$scope.addSuccess("Saved succesfully");
			},
			function(err) {}
		);
	}
	$scope.deleteProcess = function(process) {
		var war = $scope._addWarning("Deleting...");
		$resource(process.links.getHref('delete')).delete().$promise.then(
			function (data) {
        		$scope.collection.removeElement(process);
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
});
