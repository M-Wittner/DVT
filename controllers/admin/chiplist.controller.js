myApp.controller('chipListCtrl', ['$scope', '$rootScope', '$filter', 'NgTableParams', '$location', '$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', '$stateParams', '$state', function ($scope, $rootScope, $filter, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams, $stateParams, $state) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;

	if($scope.isAuthenticated == true) {
		$scope.currentUser = $cookies.getObject('loggedUser');
		$http.get(site + '/admin/chipParams')
		.then(function (response) {
			console.log(response.data);
			$scope.params = response.data;
		});
		$http.get(site + '/admin/chiplist')
		.then(function (response) {
			$scope.tableParams = new NgTableParams({
				count: 30
			}, {
				counts: [],
				total: response.data.length,
				dataset: response.data
			});
			$scope.chips = response.data;
			console.log(response.data);
		});
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	$scope.removeChip = function (chipId, chipSN) {
		$http.post('http://wigig-584/admin/removechip', chipId)
		.then(function (response) {
			if (response.data == 'success') {
				$window.scrollTo(0, 0);
				var message = 'Chip ' + chipSN + ' was deleted';
				var id = Flash.create('success', message, 3500);
			} else {
				$window.scrollTo(0, 0);
				var message = 'Error! Chip was not deleted';
				var id = Flash.create('danger', message, 3500);
			}
		});
	};
	$scope.chip = {};
	$scope.addChip = function () {
//		console.log($scope.chip);
		$http.post(site + '/admin/addchip', $scope.chip)
		.then(function (response) {
			var results = response.data;
			console.log(typeof results);
			if(typeof results == 'object'){
				results.forEach(function(res){
					var message = res.msg;
					if(res.occurred == true) {
						var id = Flash.create('danger', message, 30000);
						//				setTimeout(function(){$window.location.reload();}, 2250);
					} else {
						var id = Flash.create('success', message, 20000);
					}
				});
			}
				console.log(results);
		})
	};

	$scope.extras = function (data, paramName) {
		if(!data[paramName]) {
			data[paramName] = {};
		}
		if(!data[paramName].ext) {
			data[paramName].ext = [];
		} else {
			delete data[paramName].ext;
		}
	}
}]);
