(function() {

	angular.module('MultiClientCMS', ['ngResource', 'ngRoute']).
	config(function($routeProvider) {
		$routeProvider.
			when('/', {
				controller: 'adminController',
				controllerAs: 'adminCtrl',
				templateUrl: 'templates/pages/admin/index.html'
			});
	});

	angular.module('MultiClientCMS')
		.controller('headerController', function($scope) {
			$scope.siteName = "MultiClient CMS";
		});

	angular.module('MultiClientCMS')
		.controller('navController', function($scope, $http) {
			$scope.$root.client = 1;
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
		});
})();