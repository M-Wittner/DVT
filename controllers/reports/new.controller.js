myApp.controller('newReportCtrl', ['$scope', '$http', '$log', function ($scope, $http, $log) {
	$scope.plan = {};
	$scope.test = {};
	$scope.params = {};
	$scope.chips = {};	
	$scope.params.stationList = [
		'R-CB1',
		'R-CB2',
		'M-CB1',
		'M-CB2',
		'FS',
		'RFC/CAL',
		'PTAT/ABS/Vgb+TEMP',
	];
	
	$scope.params.nameList = [
		'TX - EVM',
		'RX - EVM',
		'Phase Shifter',
		'TX Noise Gain',
		'Rx Noise Gain',
	];
	$scope.params.chipList = [];
    $http.get('http://localhost:3000/chips/all')
	.then(function(response){
		var result = [];
		var count = 0;
		for (var k in response.data){
			if(response.data.hasOwnProperty(k)){
			   $scope.params.chipList.push(response.data[k].serial_num);
			   ++count;
			   }
		}
//		console.log($scope.params.chipList);
	});
	
	$scope.params.tempList = [
		'-30 C',
		'0 C',
		'25 C',
		'105 C',
	];
	
	$scope.params.chList = [
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
	];
	
	$scope.params.antList = [
		'0',
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
		'8',
		'9',
		'10',
		'11',
		'12',
		'13',
		'14',
		'15',
		'16',
		'17',
		'18',
		'19',
		'20',
		'21',
		'22',
		'23',
		'24',
		'25',
		'26',
		'27',
		'28',
		'29',
		'30',
		'31',
	];

	$scope.status = {
		isopen: false
	};

	$scope.toggled = function (open) {
		$log.log('Dropdown is now: ', open);
	};

	$scope.toggleDropdown = function ($event) {
		$event.preventDefault();
		$event.stopPropagation();
		$scope.status.isopen = !$scope.status.isopen;
	};

	$scope.appendToEl = angular.element(document.querySelector('#dropdown-long-content'));
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
	
	$scope.updateTest = function(){
		$scope.test = newTest;
		console.log("directive");
//		console.log($scope.plan);
		console.log($scope.$$childTail.test);
		console.log($scope.test);
		console.log($scope);
	}
	
	$scope.addPlan = function() {
		$scope.test = $scope.$$childTail.$$childTail.test;
		$scope.chips = $scope.$$childTail.$$childTail.chips;
		$http.post('http://localhost:3000/plans/create', {plan: $scope.plan, test: $scope.test})
		.then(function(response){
			console.log(response.data);
		})
	};
	
	$scope.addChip = function(){
//		console.log($scope);
		$http.post('http://localhost:3000/chips/create', {chip: $scope.chips})
		.then(function(response){
			console.log(response.data);
//			console.log($scope);
		})
	}

}]);