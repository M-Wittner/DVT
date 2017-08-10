myApp.controller('testCtrl', ['$scope', '$http', '$log', function ($scope, $http, $log) {
	$scope.test = {};
	$scope.params = {};
	$scope.lock = false;
	
	$scope.addTest1 = function(){
		$scope.planParams.push($scope.test);
		console.log($scope.planParams);
		console.log($scope.test)
		$scope.lock = true;
	};
	
	$scope.editToggle = function(){
		$scope.lock = !$scope.lock;
		$scope.planParams.splice($scope.planParams.length-1,1);
	};
	
	
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
	
	
	
	$scope.addChip = function(){
		console.log($scope);
//		console.log($scope.chips);
//		$http.post('http://localhost:3000/chips/create', {chip: $scope.chips})
//		.then(function(response){
//			console.log(response.data);
////			console.log($scope);
//		})
	}

}]);