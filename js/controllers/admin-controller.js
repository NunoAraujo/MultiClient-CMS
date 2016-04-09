(function() {
	angular.module('MultiClientCMS')
		.controller('adminController', function($scope, $http) {
			$scope.email = "";
			$scope.password = "";
			$scope.errorMsg = "";
	    	$scope.$root.user = {
	    		id: "",
	    		name: "",
	    		email: "",
	    		isAdmin: false,
	    		loggedIn: false,
	    	};
	    	$scope.sitesList = {};
	    	$scope.sitesListHeader = [];

			$scope.login = function() {
		  		$http.get("/api/loginUser", {params:{
		  			"apiKey": "testKey",
		  			"token": "testToken",
		  			"email": $scope.email,
		  			"password": $scope.password,
		  		}})
	    		.success(function (data) {
	    			if('result' in data) {
		  				$scope.$root.user = data.result;
		  				$scope.$root.user.loggedIn = true;
		  				$scope.getClientsList();
	    			} else {
	    				$scope.errorMsg = data.error;
	    			}
	    		});
		  	};

		  	$scope.getClientsList = function() {
		  		$http.get("/api/getClientsList", {params:{
		  			"apiKey": "testKey",
		  			"token": "testToken",
		  			"userId": $scope.$root.user.id,
		  		}})
	    		.success(function (data) {
	    			if('result' in data) {
		  				$scope.sitesList = data.result;
						for(var k in data.result[0]) $scope.sitesListHeader.push(k);
	    			} else {
	    				$scope.errorMsg = data.error;
	    			}
	    		});
		  	};
		});
})();