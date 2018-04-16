myApp.controller('viewPlanCtrl', ['$scope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', 'LS', '$cookies', function ($scope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, LS, $cookies) {
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
		var site = testParams.site;
		var scope = $scope;
	
	if($scope.isAuthenticated == true) {	
	$http.post(site+'/plans/show', $routeParams.id)
	.then(function(response){
		console.log(response.data);
		$scope.plan = response.data.tests;
		if(response.data.fs.length > 0){
			response.data.fs.forEach(function(elem){
				$scope.plan.tests.push(elem);
			})
		}
//		console.log(response.data);
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
			var message = 'Plan Deleted Succesfully!';
			var id = Flash.create('success', message, 3500);
			$location.path('/plans');
		});
	};
	
	$scope.removeTest = function() {
		$http.post(site+'/plans/removeTest', this.test.id)
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
		});
	};
	$scope.removeTestFS = function() {
		$http.post(site+'/plans/removeTestFS', this.test.id)
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
	
	$scope.chipStatus = function(chip, testId, index){
//		var param = this.param;
//		var test = $scope.plan.tests.filter(test => test.id == param.test_id)[0];
//		var chip = test.chips.filter(chip => chip.id == param.id)[0];
		$http.post(site+'/plans/chipstatus', {chip: chip, user: $scope.user})
		.then(function(response){
			var chip_r = response.data.chip.chip_r_sn;
			var chip_m = response.data.chip.chip_m_sn;
			var message = 'Chips '+chip_r+' '+chip_m +' Status Updated!';
			var id = Flash.create('success', message, 3500);
			$window.scrollTo(0, 0);
			setTimeout(function(){$window.location.reload();}, 1500);
//			chip.status = response.data.chip.status;
//			console.log(response.data);
//			console.log(param);
		});
//		console.log(test);
	}
	$scope.tempStatus = function(temp, testId){
		$http.post(site+'/plans/tempstatus', {temp: temp, planId: $routeParams.id, testId: testId})
		.then(function(response){
			console.log(response.data);
			temp = response.data.temp.temp;
			var message = 'Temperature '+temp+' Status Updated!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
		});
	}
	
	$scope.hotStatus = function(chip, testId){
		chip.hotCold = 'hot';
		$http.post(site+'/plans/hotcoldstatus', chip)
		.then(function(response){
			console.log(response.data);
			if(response.data = true){
				var message = 'chip '+chip.hotCold+' Status Updated!';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2250);	
				}
		});
	}	
	$scope.coldStatus = function(chip, testId){
		chip.hotCold = 'cold';
		$http.post(site+'/plans/hotcoldstatus', chip)
		.then(function(response){
			console.log(response.data);
			if(response.data = true){
				var message = 'chip '+chip.hotCold+' Status Updated!';
				var id = Flash.create('success', message, 3500);
				setTimeout(function(){$window.location.reload();}, 2250);	
				}
		});
	}
	
	$scope.path = function(path){
		console.log(path);
	}
	
	$scope.filterByName = function(params, word){
		var data = params.filter(param => param.param_name.includes(word));
		if(typeof data[0] != 'undefined'){
			return true;
		} else{
			return false;
		}
	}
	
	$scope.xifStatus = function(xif){
//		console.log(xif);
		$http.post(site+'/plans/xifstatus', xif)
		.then(function(response){
			this.xif = response.data[0];
			var message = 'XIF '+this.xif.xif+' Status Updated!';
			var id = Flash.create('success', message, 3500);
			setTimeout(function(){$window.location.reload();}, 2250);
//			console.log(this.xif);
//			$scope.$apply();
//			$route.reload();
		});
	};
}]);