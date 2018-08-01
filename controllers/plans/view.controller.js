myApp.controller('viewPlanCtrl', ['$scope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', 'LS', '$cookies', 'testParams', function ($scope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, LS, $cookies, testParams) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
		var site = testParams.site;
		var scope = $scope;
	
	if($scope.isAuthenticated == true) {	
	
	$http.post(site+'/plans/show_v1', $routeParams.id)
	.then(function(response){
		console.log(response.data);
		$scope.plan = response.data;
	});

	$scope.user = {};
	$scope.user.id = $cookies.getObject('loggedUser').userId;
	$scope.user.username = $cookies.getObject('loggedUser').username;
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	$scope.toggleTest = function(index){
//		console.log(index);
		$scope.toggleCollapse = !$scope.toggleCollapse;
		setTimeout($scope.toggleFade = $scope.toggleFade, 1500);
	}
	
	$scope.params = testParams.params;
	$scope.lock = true;
	
	$scope.removePlan = function() {
		$http.post(site+'/plans/removePlan', this.plan.id)
		.then(function(response){
			console.log(response.data);
			if(response.data == 'true'){
				var message = 'Plan Deleted Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
			} else{
				var message = 'Plan Deleted Succesfully!';
				var id = Flash.create('danger', message, 0);
			}
		});
	};
	
	$scope.removeTest = function($testId) {
		$http.post(site+'/plans/removeTest', $testId)
		.then(function(response){
			if(response.data = 'success'){
				$window.scrollTo(0, 0);
				var message = 'Test Deleted Succesfully!';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2250);
			} else{
				$window.scrollTo(0, 0);
				var message = 'Test Was Not Deleted!';
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
//				console.log(response.data);
		});
	};
	
	$scope.sendMail = function(){
		$http.post(site+'/plans/sendMail', $scope.plan)
		.then(function(response){
			console.log(response.data);
		})
	}
	
	$scope.removeComment = function() {
		$http.post(site+'/plans/removeComment', this.comment.comment_id)
		.then(function(response){
//			console.log(response.data);
			$window.scrollTo(0, 0);
			var message = 'Comment Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
		});
	};
	
	$scope.chipStatus = function(chip){
		chip.flag = null;
		$http.post(site+'/plans/chipstatus', {chip: chip, user: $scope.user})
		.then(function(response){
			console.log(response.data);
			var keys = Object.keys(response.data);
			var key = keys[0];
			var username = keys[1]
			chip[key] = response.data[key];
			chip.username = response.data[username];
//			var message = 'Chip ' + chip.chip_sn+'-'+chip.chip_process_abb + ' '+ key + ' has been updated <strong>(test: #' + chip.test_id +')</strong>';
//			var id = Flash.create('success', message, 6000);
		});
	}
	
	$scope.hotStatus = function(chip){
		chip.flag = 'hot';
		$http.post(site+'/plans/chipstatus', {chip: chip, user: $scope.user})
		.then(function(response){
			console.log(response.data);
			var key = Object.keys(response.data)[0];
			chip[key] = response.data[key];
//				var message = 'Chip ' + chip.chip_sn+'-'+chip.chip_process_abb + ' '+ key + ' has been updated <strong>(test: #' + chip.test_id +')</strong>';
//				var id = Flash.create('success', message, 6000);
		});
	}	
	$scope.coldStatus = function(chip){
		chip.flag = 'cold';
		$http.post(site+'/plans/chipstatus', {chip: chip, user: $scope.user})
		.then(function(response){
			console.log(response.data);
			var key = Object.keys(response.data)[0];
			chip[key] = response.data[key];
//			var message = 'Chip ' + chip.chip_sn+'-'+chip.chip_process_abb + ' '+ key + ' has been updated <strong>(test: #' + chip.test_id +')</strong>';
//			var id = Flash.create('success', message, 6000);
		});
	}
	
	$scope.filterByName = function(params, word){
		var data = params.filter(param => param.param_name.includes(word));
		if(typeof data[0] != 'undefined'){
			return true;
		} else{
			return false;
		}
	}
}]);