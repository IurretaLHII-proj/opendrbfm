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
		operationType: {
			getAll: function() {
				return $res('/process/op/type/json').get().$promise.then(
					function(data) {
						return data._embedded.items;
					},
					function(err) {
					}
				);
			},
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
			delete: function(op) {
				return $res(op._links.delete.href).delete().$promise.then(
					function(data) {
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
		},
		simulation: {
			delete: function(item) {
				return $res(item.links.getHref('delete')).delete().$promise.then(
					function(data) {
						return data;
					},
					function(err) {
						return err;
					}
				);
			},
		},
		stage: {
			prepare: function(item) {
				var raw = angular.copy(item);
				angular.forEach(raw.children, function(item, i) { 
					raw.children[i] = {id:item._embedded.id}; 
				});
				angular.forEach(raw.operations, function(op,i) {
					raw.operations[i] = {id:op._embedded ? op._embedded.id : null};
				});
				raw.parent   = raw.parent.id;
				raw.material = raw.material.id;
				delete raw.process;
				delete raw.hints;
				delete raw.operationTypes;
				delete raw._embedded;
				delete raw._links;
				delete raw._parent;
				return raw;
			},
			getHints: function(item) {
				return $res(item._links.hints.href).get().$promise.then(
					function(data) {
						return data;
					},
					function(err) {
					}
				);
			},
			save: function(item) {
				//uri = item._embedded ? 
				//	item._embedded._links.edit.href : 
				//	item.process._links.stage.href;

				raw = {stage: this.prepare(item)}
				return $res(item.process._links.stage.href).save(raw).$promise.then(
					function(data) {
						return data;
					},
					function(err) {
						throw err;
					}
				);
			},
		}
	}
}]);
