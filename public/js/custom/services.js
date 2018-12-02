App.service('api', ['$resource', function($res) {
	return {
		material : {
			getAll: function() {
				return $res('/process/material/json').get().$promise.then(
					function(data) {
						return data._embedded.items;
					},
					function(err) {
					}
				);
			}
		}
	}
}]);
