myApp.controller('calendarCtrl', ['$scope', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', 'calendarConfig', 'moment', function ($scope, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams, calendarConfig, moment) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
//	console.log($scope.currentUser);
	
//	$scope.user = $scope.currentUser.username;
	
	if($scope.isAuthenticated == true) {
		$scope.calendarView = 'month';
		$scope.viewDate = new Date();
		$scope.events = events = [
      {
        title: 'An event',
        color: calendarConfig.colorTypes.warning,
        startsAt: moment().startOf('week').subtract(2, 'days').add(8, 'hours').toDate(),
        endsAt: moment().startOf('week').add(1, 'week').add(9, 'hours').toDate(),
        draggable: true,
        resizable: true,
//        actions: actions
      }, {
        title: '<i class="glyphicon glyphicon-asterisk"></i> <span class="text-primary">Another event</span>, with a <i>html</i> title',
        color: calendarConfig.colorTypes.info,
        startsAt: moment().subtract(1, 'day').toDate(),
        endsAt: moment().add(5, 'days').toDate(),
        draggable: true,
        resizable: true,
//        actions: actions
      }, {
        title: 'This is a really long event title that occurs on every year',
        color: calendarConfig.colorTypes.important,
        startsAt: moment().startOf('day').add(7, 'hours').toDate(),
        endsAt: moment().startOf('day').add(19, 'hours').toDate(),
        recursOn: 'year',
        draggable: true,
        resizable: true,
//        actions: actions
      }
    ];
//	} else {
//		var message = 'Please Login first!';
//		var id = Flash.create('danger', message, 3500);
//		$location.path('/');
	};
}]);