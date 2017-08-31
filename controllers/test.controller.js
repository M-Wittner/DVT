myApp.controller('testCtrl', ['$scope', '$http', '$log', 'testParams', function ($scope, $http, $log, testParams) {
	
	$scope.testParams = testParams;
	$scope.test = {};
	$scope.planParams;
	$scope.lock = false;
	
	$scope.addTest1 = function(){
		if ($scope.test.hasOwnProperty() ){
			console.log('wow');
		}else{
			console.log('not wow');
		}
			
//		$scope.planParams.push($scope.test);
//		$scope.lock = true;
//		console.log($scope.planParams);
	};
	
	$scope.editToggle = function(){
		$scope.lock = !$scope.lock;
		$scope.planParams.splice($scope.planParams.length-1,1);
	};
	
	$scope.test.calc = true;
	$scope.showCalc = function(){
		$scope.test.calc = !$scope.test.calc;
	};
	
	$scope.addChip = function(){
//		console.log($scope);
//		console.log($scope.chips);
//		$http.post('http://localhost:3000/chips/create', {chip: $scope.chips})
//		.then(function(response){
//			console.log(response.data);
////			console.log($scope);
//		})
	}

}]);