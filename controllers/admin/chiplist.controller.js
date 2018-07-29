myApp.controller('chiplistCtrl', ['$scope', 'NgTableParams', '$location','$http', '$routeParams', 'Flash', 'AuthService', '$window', 'testParams', '$cookies', function ($scope, NgTableParams, $location, $http, $routeParams, Flash, AuthService, $window, testParams,  $cookies) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthenticated = true;
	var site = testParams.site;
	
	if($scope.isAuthenticated == true) {
		$scope.currentUser = $cookies.getObject('loggedUser');
		console.log($scope.currentUser.rank);
		$http.get(site+'/admin/chiplist')
		.then(function(response) {
			$scope.tableParams = new NgTableParams({count:30}, {
				counts:[],
				total: response.data.length,
				dataset: response.data
			});
			$scope.chips=response.data;
			console.log(response.data);
		});

		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		};
	$scope.removeChip = function(chipId, chipSN){
		$http.post('http://wigig-584/admin/removechip', chipId)
		.then(function(response){
			if(response.data == 'success'){
				$window.scrollTo(0, 0);
				var message = 'Chip '+chipSN+' was deleted';
				var id = Flash.create('success', message, 3500);
			} else{
				$window.scrollTo(0, 0);
				var message = 'Error! Chip was not deleted';
				var id = Flash.create('danger', message, 3500);
			}
		});
	};
	$scope.lineup = function(){
		$http.post(site+'/plans/lineups')
		.then(function(response){
			console.log(response.data);
		})
	}
}]);