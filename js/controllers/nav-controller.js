(function() {
	angular.module('MultiClientCMS')
		.controller('navController', function($scope, $location, $http, $cookies) {
			$scope.$root.client = 1;
			$scope.$root.schemeId;
			$scope.$root.user;
			$scope.areas = {};

			$http.get("/api/getNavAreas", {params:{
	  			"apiKey": "testKey",
	  			"token": "testToken",
	  			"clientId": $scope.$root.client,
	  		}})
			.success(function (data) {
				if('result' in data) {
	  				$scope.areas = data.result;
				} else {
					console.log(data.error);
				}
			});

			$scope.goTo = function(schemeId) {
				$location.path('/scheme/list/' + schemeId);
			};

			if (typeof $cookies.getObject('user') === 'undefined') {
				$location.path('/');	
			} else {
				$scope.$root.user = $cookies.getObject('user');
			}
		});
})();