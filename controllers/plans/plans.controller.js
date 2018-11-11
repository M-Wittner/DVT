myApp.controller('plansCtrl', ['$scope', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', function ($scope, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
//	console.log($scope.currentUser);
//	console.log($scope.currentUser);
	
//	$scope.user = $scope.currentUser.username;
	
	if($scope.isAuthenticated) {
		$scope.plans = {};
		$http.get(site+'/plans')
		.then(function(response) {
			console.log(response.data);
			var data = response.data;
			$scope.webTableParams = new NgTableParams({count:12}, {
				counts:[],
				total: data.web.length,
				dataset: data.web
			})
			$scope.plans.web = data.web;
			$scope.labTableParams = new NgTableParams({count:12}, {
				counts:[],
				total: data.lab.length,
				dataset: data.lab
			})
			$scope.plans.lab = data.lab;
		});
		
		$scope.view = function(data){
			$location.path('/plans/'+data);
		};
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	$scope.selected = [];
	$scope.deleteSelected = function(){
		if($scope.selected.length > 0){
			$http.post(site+'/plans/deletePlans', $scope.selected)
			.then(function(response){
				console.log(response.data);
				if(typeof response.data == "object"){
					response.data.forEach(function(error){
						var message = "<strong> Plan #" + error.source +"</strong>" + ": " + error.msg;
						if(error.occurred == true){
							var id = Flash.create('danger', message, 0);
						}else{
							var id = Flash.create('success', message, 0);
						}
						$window.scrollTo(0, 0);
					})	
				}else{
					var message = response.data;
					var id = Flash.create('success', message, 3500);
				}
			})
		}else{
			var message = "No Plans Selected";
			var id = Flash.create('danger', message, 3500);
		}
	}
	
	$scope.chipStatus = function(chip, flag){
		chip.flag = flag;
		$http.post(site+'/plans/chipstatus', {chip: chip, user: $scope.currentUser})
		.then(function(response){
			console.log(response.data);
			var keys = Object.keys(response.data);
			var key = keys[0];
			var username = keys[1];
			chip[key] = response.data[key];
			chip.username = response.data[username];
			var message = 'Chip ' + chip.chip_sn+'-'+chip.chip_process_abb + ' '+ key + ' has been updated <strong>(test: #' + chip.test_id +')</strong>';
			var id = Flash.create('success', message, 6000);
		});
	};
	
	$scope.tooltip= function(id){
		$http.post(site+'/plans/planStatus', id)
		.then(function(response){
//			console.log(response.data);
			$scope.tests = response.data;
		});
	}
}]);