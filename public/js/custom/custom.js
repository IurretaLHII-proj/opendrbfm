var App = angular.module('myApp', [
		'ngResource',
		'ngSanitize',
		'ngAnimate',
		'textAngular',
		'ui.sortable',
		'ui.bootstrap',
		'autoCompleteModule'
		]);

App.config(function($rootScopeProvider) {
    $rootScopeProvider.digestTtl(20); //FIXME bad for performance: some number bigger then 10
})

if (!angular.lowercase) angular.lowercase = str => angular.isString(str) ? str.toLowerCase() : str;

App.config(function($interpolateProvider) {
	$interpolateProvider.startSymbol('{[').endSymbol(']}');
});

App.directive('compile', ['$compile', function ($compile) {
	return function(scope, element, attrs) {
          var ensureCompileRunsOnce = scope.$watch(
            function(scope) {
               // watch the 'compile' expression for changes
              return scope.$eval(attrs.compile);
            },
            function(value) {
              // when the 'compile' expression changes
              // assign it into the current DOM
              element.html(value);

              // compile the new DOM and link it to the current
              // scope.
              // NOTE: we only compile .childNodes so that
              // we don't get into infinite loop compiling ourselves
              $compile(element.contents())(scope);
                
              // Use Angular's un-watch feature to ensure compilation only happens once.
              ensureCompileRunsOnce();
            }
        );
    };
}]);

App.filter('unique', function() {
	return function(collection) {
		var output = [];
		angular.forEach(collection, e => {
			if (output.indexOf(e) === -1) {
				output.push(e);
			}
		});
		return output;
	}
});

App.directive('fileChange', ['$resource', '$parse', function($res, $parse) {
    return {
        restrict : 'A',
        //scope : {index: "=fileIndex"}, 
        scope: true,
        link: function(scope, elem, attrs) {
            elem.bind('change', function(ev) {
                var onChangeHandler = scope.$eval(attrs.fileChange);
                onChangeHandler(ev, scope.$eval(attrs.fileEntity), scope.$eval(attrs.fileIndex)); 
				scope.$apply();
            });
        }
    }
}]);

App.directive('includeReplace', function () {
    return {
        require: 'ngInclude',
		restrict: 'A', /* optional */
        link: function (scope, el, attrs) {
            el.replaceWith(el.children());
        }
    };
});

App.directive('ngConfirmClick', [function() {
	return {
		link: function(scope, elem, attrs) {
			var msg    = attrs.ngConfirmClick || "Are you sure?";
			var action = attrs.confirmedClick;
			elem.bind('click', function(ev) {
				if (window.confirm(msg)){
					scope.$eval(action);
				}
			});
		}
	}
}]);

var parseQueryString = function() {
	var str = window.location.search;
	var objURL = {};

	str.replace(
	    new RegExp( "([^?=&]+)(=([^&]*))?", "g" ),
	    function( $0, $1, $2, $3 ){
	        objURL[ $1 ] = $3;
	    }
	);
	return objURL;
};

App.controller('MainCtrl', function($scope, $uibModal, $resource, $timeout) {

	$scope.params 	  = parseQueryString();
	$scope.closeError = function(err) {
		var i = $scope.messages.errors.indexOf(err);
		$scope.messages.errors.splice(i, 1);
	}
	$scope.addError = function(err) {
		$scope.messages.errors.push(err);
		$timeout(function() {
			$scope.closeError(err);
		}
		, 1500);
	}
	$scope.closeSuccess = function(succ) {
		var i = $scope.messages.success.indexOf(succ);
		$scope.messages.success.splice(i, 1);
	}
	$scope.addSuccess = function(succ) {
		$scope.messages.success.push(succ);
		$timeout(function() {
			$scope.closeSuccess(succ);
		}
		, 1500);
	}

	$scope._closeWarning = function(war) {
		var i = $scope.messages.warnings.indexOf(war);
		$scope.messages.warnings.splice(i, 1);
	}
	$scope._addWarning = function(msg) {
		var war = msg; 
		$scope.messages.warnings.push(war);
		return war;
	}

	$scope.messages = {
		success: [],
		errors: [],
		warnings: [],
	};


	$scope.isNote = function(item) { return item instanceof MANote; }
	$scope.isProcess = function(item) { return item instanceof MAProcess; }
	$scope.isVersion = function(item) { return item instanceof MAVersion; }
	$scope.isStage = function(item) { return item instanceof MAStage; }
	$scope.isHint = function(item) { return item instanceof MAHint; }
	$scope.isHintReason = function(item) { return item instanceof MAHintReason; }
	$scope.isHintInfluence = function(item) { return item instanceof MAHintInfluence; }
	$scope.isSimulation = function(item) { return item instanceof MASimulation; }
	$scope.isComment = function(item) { return item instanceof MAComment; }

	$scope.isArray  = function(e) {return Array.isArray(e); }
	$scope.isObject = function(e) {return typeof e === "object"; }

	$scope.eventLabel = function(ev) {
		return ev;
	}
	$scope.classLabel = function(e) {
		if ($scope.isNote(e)) {
			if (e.class == 'MA\\Entity\\Note\\HintReason') return 'Reason note';
			if (e.class == 'MA\\Entity\\Note\\HintInfluence') return 'Influence note';
			if (e.class == 'MA\\Entity\\Note\\HintSuggestion') return 'Suggestion note';
			if (e.class == 'MA\\Entity\\Note\\HintEffect') return 'Effect note';
			if (e.class == 'MA\\Entity\\Note\\HintPrevention') return 'Prevention note';
			return "Note";
		}
		if ($scope.isComment(e)) {
			if (e.class == 'MA\\Entity\\Comment\\Version') return "Version comment";
			if (e.class == 'MA\\Entity\\Comment\\Stage') return "Stage comment";
			if (e.class == 'MA\\Entity\\Comment\\Hint') return "Error comment";
			if (e.class == 'MA\\Entity\\Comment\\HintReason') return "Error reason comment";
			if (e.class == 'MA\\Entity\\Comment\\HintInfluence') return "Error influence comment";
			if (e.class == 'MA\\Entity\\Comment\\HintSuggestion') return "Error suggestion comment";
			if (e.class == 'MA\\Entity\\Comment\\HintEffect') return "Error effect comment";
			if (e.class == 'MA\\Entity\\Comment\\HintPrevention') return "Error prevention comment";
			if (e.class == 'MA\\Entity\\Comment\\Simulation') return "Simulation comment";
			if (e.class == 'MA\\Entity\\Comment\\Note') return "Note comment";
			return "Comment";
		}
		if ($scope.isProcess(e)) return "Process";
		if ($scope.isVersion(e)) return "Version";
		if ($scope.isStage(e)) return "Stage";
		if ($scope.isHint(e)) return "Error";
		if ($scope.isHintReason(e)) return "Error reason";
		if ($scope.isHintInfluence(e)) return "Error influence";
		if ($scope.isSimulation(e)) return "Simulation";
	}
	$scope.commentDetail = function(comment) {
		$resource(comment.links.getHref()).get().$promise.then(
			function (data) {
				var modal = $uibModal.open({
					animation: true,
					templateUrl : '/js/custom/tpl/modal/comment-detail.html',
					controller: '_CommentDetailCtrl',	
					size: 'lg',
					scope: $scope,
					resolve: {source : new EMAComment(data)}
				});
			},
			function (err) {
			}
		);
	}

	var userColors = [];
	var colors = [
		'ForestGreen', 
		'OrangeRed', 
		'DodgerBlue', 
		'LightSeaGreen', 
		'IndianRed', 
		'GoldenRod',
		'Indigo', 
		'HotPink', 
		'YellowGreen',
		'DarkRed',
		'Black',
		'LightSalmon',
		'Gray',
	];
	$scope.userColor = function(user) {
		if (userColors[user.id]) {
			return userColors[user.id];
		}
		if (colors.length) {
			userColors[user.id] = colors.shift();
			return userColors[user.id];
		}
		return 'LightGray';
	}
});

App.controller('CollectionCtrl', function($scope, $resource) {
	$scope.collection = null;

	$scope.init = function(collection) {
		$scope.collection = collection;
		console.log(collection);
	};

	$scope.more = function() {
		$resource($scope.collection._links.next.href).get().$promise.then(
			function (data) {
				angular.forEach(data._embedded.items, function(item) {
					//$scope.collection._embedded.items.unshift(item);
					$scope.collection._embedded.items.push(item);
				});
				$scope.collection._links = data._links;
				$scope.collection.page   = data.page;
			},
			function (err) {
				console.log(err);
			}	
		);
	}
});
