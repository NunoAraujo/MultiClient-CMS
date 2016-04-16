(function() {
	angular.module('MultiClientCMS')
		.controller('headerController', function($scope, $location) {
			$scope.siteName = "MultiClient CMS";

			$scope.goToAdmin = function(){
				$location.path('/');
			};
		});
})();