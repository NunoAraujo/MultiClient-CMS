(function() {
	angular.module('MultiClientCMS')
		.controller('schemeController', function($scope, $http, $routeParams) {
			$scope.schemeList = {};
	    	$scope.schemeListHeader = [];
	    	$scope.groupBy = "";
	    	$scope.groupByValue = "";
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
    				var result = data.result;
    				$scope.schemeList = result.items;
					$scope.schemeListHeader = result.headers;
    				$scope.groupBy = result.groupBy;
    			} else {
    				$scope.errorMsg = data.error;
    			}
    		});

    		$scope.goToItem = function(itemId) {
    			if($scope.groupBy == "" || ($scope.groupBy != "" && $scope.groupByValue != "")) {

    			} else {
    				$http.get("/api/getSchemeList", {params:{
			  			"apiKey": "testKey",
			  			"token": "testToken",
			  			"clientID": $scope.$root.client,
			  			"schemeID": $scope.$root.schemeId,
			  			"groupByValue": itemId
			  		}})
		    		.success(function (data) {
		    			if('result' in data) {
		    				var result = data.result;
		    				$scope.schemeList = result.items;
							$scope.schemeListHeader = result.headers;
		    				$scope.groupBy = result.groupBy;
		    				$scope.groupByValue = itemId;
		    			} else {
		    				$scope.errorMsg = data.error;
		    			}
		    		});
    			}
    		}
		});
})();