myApp.controller('stationlistCtrl', ['$scope', 'NgTableParams','$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', 'testParams', function ($scope, NgTableParams,$location, $http, $routeParams, Flash, AuthService, $window, testParams ) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	
	if($scope.isAuthenticated == true) {
		$http.get(site+'/admin/stationlist')
		.then(function(response) {
			console.log(response.data);
			var data = response.data;
			$scope.TableParams = new NgTableParams({count:12}, {
				counts:[],
				total: data.length,
				dataset: data
			})
			$scope.stations=response.data;
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	$scope.removeStation = function(){
		var stationName = this.station.station;
		$http.post(site+'/admin/removestation', this.station)
		.then(function(response){
			if(response.data == 'success'){
				$window.scrollTo(0, 0);
				var message = 'Station: '+stationName+' was deleted';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2000);
			} else{
				$window.scrollTo(0, 0);
				var message = 'Error! Station was not deleted';
				var id = Flash.create('danger', message, 3500);
			}
		});
//		console.log(this.station);
	}
}]);