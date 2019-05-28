
App.controller('_ProcessCollectionCtrl', function($scope, $resource, $location) {

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
		if ($scope.machine)	 		 query.machine 	   = $scope.machine;
		if ($scope.line)	 		 query.line 	   = $scope.line;
		if ($scope.piece)	 		 query.piece 	   = $scope.piece;
		query.order    = $scope.order;
		query.criteria = $scope.criteria;
		return query;
	}

	$scope.init = function() {
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
						$scope.search();
					}, 
					function(err){}
				);
			}, 
			function(err){}
		);
	}

	$scope.selOrder		 = function(value) { $scope.order	   = value; }
	$scope.selCriteria	 = function(value) { $scope.criteria   = value; }
	$scope.selComplexity = function(value) { $scope.complexity = value; }
	$scope.selOwner 	 = function(value) { $scope.owner 	   = value; }
	$scope.selCustomer 	 = function(value) { $scope.customer   = value; }

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

	/*$scope.rmProcess = function() {$scope.process = null;}
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
	};*/
});
