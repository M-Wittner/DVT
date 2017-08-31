myApp.controller('testCtrl', ['$scope', '$http', '$log', 'testParams', function ($scope, $http, $log, testParams) {
	
	$scope.testParams = testParams;
	$scope.test = {};
	$scope.planParams;
	$scope.lock = false;
	
	$scope.addTest1 = function(){
//		if(($scope.test.hasOwnProperty('priority') && $scope.test.hasOwnProperty('lineup') && $scope.test.hasOwnProperty('station') && $scope.test.hasOwnProperty('chips') && $scope.test.hasOwnProperty('pinFrom') && $scope.test.hasOwnProperty('pinTo') && $scope.test.hasOwnProperty('pinStep') && $scope.test.hasOwnProperty('pinAdd') && $scope.test.hasOwnProperty('temp') && $scope.test.hasOwnProperty('channel') && $scope.test.hasOwnProperty('mcs') && $scope.test.hasOwnProperty('voltage') && $scope.test.hasOwnProperty('notes')) || ($scope.test.hasOwnProperty('priority') && $scope.test.hasOwnProperty('lineup') && $scope.test.hasOwnProperty('station') && $scope.test.hasOwnProperty('chips') && $scope.test.hasOwnProperty('temp') && $scope.test.hasOwnProperty('channel') && $scope.test.hasOwnProperty('xif') && $scope.test.hasOwnProperty('voltage') && $scope.test.hasOwnProperty('notes'))){
//			console.log('wow');
//	    } else {
//			  console.log('choose station');
//	  	}
			
		$scope.planParams.push($scope.test);
		$scope.lock = true;
		console.log($scope.planParams);
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