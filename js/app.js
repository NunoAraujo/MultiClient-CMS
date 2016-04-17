(function() {
	angular.module('MultiClientCMS', ['ngResource', 'ngRoute', 'ngCookies']).
	config(function($routeProvider) {	
		$routeProvider
			.when('/', {
				controller: 'adminController',
				controllerAs: 'adminCtrl',
				templateUrl: 'templates/pages/admin/index.html'
			}).when('/scheme/list/:id', {
				controller: 'schemeController',
				controllerAs: 'schemeCtrl',
				templateUrl: 'templates/pages/scheme/list.html'
			}).otherwise({redirectTo: '/'});
	});

	
})();