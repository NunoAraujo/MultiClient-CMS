(function() {
	angular.module('MultiClientCMS')
		.controller('schemeController', function($scope, $http, $routeParams) {
			$scope.schemeList = {};
	    	$scope.schemeListHeader = [];
	    	$scope.$root.client;
	    	$scope.$root.schemeId = $routeParams.id;
	  		$http.get("/api/getSchemeList", {params:{
	  			"apiKey": "testKey",
	  			"token": "testToken",
	  			"clientID": $scope.$root.client,
	  			"schemeID": $scope.$root.schemeId,
	  		}})
    		.success(function (data) {
    			if('result' in data) {
	  				$scope.schemeList = data.result.items;
					$scope.schemeListHeader = data.result.headers;
    			} else {
    				$scope.errorMsg = data.error;
    			}
    		});
		});
})();