myApp.controller('viewPlanCtrl', ['$scope', '$rootScope', '$route', '$location','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', '$stateParams', '$cookies', 'NgTableParams', function($scope, $rootScope, $route, $location, $http, $routeParams, $window, Flash, AuthService, testParams, $stateParams, $cookies, NgTableParams){
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
		var site = $rootScope.site;
		var scope = $scope;
	if($scope.isAuthenticated == true){	
	
	$http.get(site+'/plans/getPlan/' + $stateParams.planId)
	.then(function(response){
		console.log(response.data);
		var data = response.data;
//		$scope.plans = [];
		$scope.plan = data;
//		$scope.plans[0].tests = data.tests;
//		$scope.plans[0].progress = data.progress;
//		console.log($scope.plan);
	});
		
	$scope.returnName = function(sweepName){
		return sweepName;
	}
	$scope.sweepsTable = function(struct){
		$scope.cols = [];
		$scope.TableParams = new NgTableParams({
			counts:[],
			total: struct.length,
			dataset: struct
		})
	}

	$scope.user = {};
	$scope.user.id = $cookies.getObject('loggedUser').id;
	$scope.user.username = $cookies.getObject('loggedUser').username;
		
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	}
	
	$scope.toggleTest = function(index){
//		console.log(index);
		$scope.toggleCollapse = !$scope.toggleCollapse;
		setTimeout($scope.toggleFade = $scope.toggleFade, 1500);
	};
	
	$scope.params = testParams.params;
	$scope.lock = true;
	
	$scope.deleteSelectedTests = function(){
		if($scope.selectedTests.length > 0){
			$http.post(site+'/plans/deleteTests', $scope.selectedTests)
			.then(function(response){
				console.log(response.data);
				if(typeof response.data == "object"){
					response.data.forEach(function(error){
						var message = "<strong> Test #" + error.source +"</strong>" + ": " + error.msg;
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
			var message = "No Tests Selected";
			var id = Flash.create('danger', message, 3500);
		}
	}
	
	$scope.sendMail = function(){
		$http.post(site+'/plans/sendMail', $scope.plan)
		.then(function(response){
			console.log(response.data);
		});
	};
	
	$scope.removeComment = function(){
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
			var username = keys[1];
			chip[key] = response.data[key];
			chip.username = response.data[username];
//			var message = 'Chip ' + chip.chip_sn+'-'+chip.chip_process_abb + ' '+ key + ' has been updated <strong>(test: #' + chip.test_id +')</strong>';
//			var id = Flash.create('success', message, 6000);
		});
	};
	
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
	};	
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
	};
	
	$scope.filterByName = function(params, word){
		var data = params.filter(param => param.param_name.includes(word));
		if(typeof data[0] != 'undefined'){
			return true;
		} else{
			return false;
		}
	};
	
	$scope.newCmt = function(testId, planId){
		var test = this.test;
		var comment = this.comment
		this.comment.test_id = testId;
		this.comment.plan_id = planId;
		this.comment.user_id = $scope.user.id;
		$http.post(site+'/plans/addComment', this.comment)
		.then(function(response){
			if(response.data.comment == comment.text) {
				$window.scrollTo(0, 0);
				var message = 'Comment Was Added successfully';
				var id = Flash.create('success', message, 5000);
				console.log(response.data);
				test.comments.push(response.data);
			} else {
				console.log(response.data);
				var message = response.data;
				var id = Flash.create('danger', message, 5000);
			}
		})
//		console.log(this.comment);
	}
}]);