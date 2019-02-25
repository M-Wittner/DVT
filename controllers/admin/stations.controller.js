myApp.controller('stationsCtrl', ['$scope', '$rootScope', '$filter', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', '$stateParams', '$state', function ($scope, $rootScope, $filter, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams, $stateParams, $state) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	function getStationTests(station){
		$http.get(site + '/admin/GetStationTests/' + station.idx)
			.then(function(response) {
			console.log(response.data);
			if($.isEmptyObject(response.data)){
				station.errors = response.data.errors;
			}else{
				station.tests = response.data;
			}
			return station;
		});
	}
	function getTestData(test){
		$http.get(site + '/admin/GetTestData/' + test.type_idx)
			.then(function(response) {
			console.log(response.data);
			if($.isEmptyObject(response.data)){
				test.errors = response.data.errors;
			}else{
				test.sweeps = response.data;
			}
			return test;
		});
	}
	
	if($scope.isAuthenticated == true) {
		$http.get(site+'/admin/stationlist')
		.then(function(response) {
			console.log(response.data);
//			var data = response.data;
//			$scope.TableParams = new NgTableParams({count:12}, {
//				counts:[],
//				total: data.length,
//				dataset: data
//			})
			$scope.stations=response.data;
		}).then(function(){
			$scope.stations[0].tests = getStationTests($scope.stations[0]);
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	
	$scope.getStationData = function(station){
		console.log(station);
		station = getStationTests(station);
	}
	$scope.getTestData = function(test){
		console.log(test);
		test = getTestData(test);
	}
	
	
//	$scope.station = {};
//	$scope.addStation = function(){
////		console.log($scope.chip);
//		$http.post('http://wigig-584/admin/addstation', $scope.station)
//		.then(function(response){
//			if(response.data == 'success'){
////				$location.path('/plans/');
//				var message = 'New station was added successfully';
//				var id = Flash.create('success', message, 5000);
//				setTimeout(function(){$window.location.reload();}, 2250);
//			}else {
//				var message = response.data;
//				var id = Flash.create('danger', message, 3500);
//				console.log(response.data);
//			}
//		})
//	};	
	
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