myApp.controller('newReportCtrl', ['$scope', '$http', '$log', function ($scope, $http, $log) {
	$scope.test = {};
	
	$scope.test.name = [
		'one',
		'two',
		'three',
		'four',
		'four2',
		'four4'
	]
	
	$scope.items = [
    'chise',
    'And another choice for you.',
    'but wait! A third!'
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

}]);