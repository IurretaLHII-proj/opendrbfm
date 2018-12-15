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
		},
		operation: {
			getAll: function() {
				return $res('/process/op/json').get().$promise.then(
					function(data) {
						return data._embedded.items;
					},
					function(err) {
					}
				);
			},
			getHints: function(op) {
				return $res(op._links.hints.href).get().$promise.then(
					function(data) {
						return data;
					},
					function(err) {
					}
				);
			}
		}
	}
}]);
