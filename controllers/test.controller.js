myApp.controller('testCtrl', ['$scope', '$http', '$log', 'testParams', function ($scope, $http, $log, testParams) {
	
	$scope.testParams = testParams;
	$scope.test = {};
	$scope.planParams;
	$scope.lock = false;
	
	$scope.addTest1 = function(){
		$scope.planParams.push($scope.test);
		$scope.lock = true;
		console.log($scope.planParams);
	};
	
	$scope.calc = false;
	$scope.showCalc = function(){
		$scope.calc = !$scope.calc;
		console.log($scope.calc);
	};
	
	$scope.editToggle = function(){
		$scope.lock = !$scope.lock;
		$scope.planParams.splice($scope.planParams.length-1,1);
	};

}]);