//myApp.service('multiselect', function($scope, $log){
//	
//	$scope.params = {};
//	
//	$scope.params.stationList = [
//		'R-CB1',
//		'R-CB2',
//		'M-CB1',
//		'M-CB2',
//		'FS',
//		'RFC/CAL',
//		'PTAT/ABS/Vgb+TEMP',
//	];
//	
//	$scope.params.nameList = [
//		'TX - EVM',
//		'RX - EVM',
//		'Phase Shifter',
//		'TX Noise Gain',
//		'Rx Noise Gain',
//	];
//	
//	$scope.params.chipList = [
//    'YA95100006',
//    'YA95100007',
//    'YA95100008',
//    'YA95100009',
//    'YA95100010',
//    'YA95100011',
//    'YA95100012'
//  ];
//	
//	$scope.params.tempList = [
//		'-30 C',
//		'0 C',
//		'25 C',
//		'105 C',
//	];
//	
//	$scope.params.chList = [
//		'1',
//		'2',
//		'3',
//		'4',
//		'5',
//		'6',
//		'7',
//	];
//	
//	$scope.params.antList = [
//		'0',
//		'1',
//		'2',
//		'3',
//		'4',
//		'5',
//		'6',
//		'7',
//		'8',
//		'9',
//		'10',
//		'11',
//		'12',
//		'13',
//		'14',
//		'15',
//		'16',
//		'17',
//		'18',
//		'19',
//		'20',
//		'21',
//		'22',
//		'23',
//		'24',
//		'25',
//		'26',
//		'27',
//		'28',
//		'29',
//		'30',
//		'31',
//	];
//
//	$scope.status = {
//		isopen: false
//	};
//
//	$scope.toggled = function (open) {
//		$log.log('Dropdown is now: ', open);
//	};
//
//	$scope.toggleDropdown = function ($event) {
//		$event.preventDefault();
//		$event.stopPropagation();
//		$scope.status.isopen = !$scope.status.isopen;
//	};
//
//	$scope.appendToEl = angular.element(document.querySelector('#dropdown-long-content'));
//});