myApp.controller('viewPlanCtrl', ['$scope', '$rootScope', '$state', '$route', 'GetData','$http', '$routeParams', '$window', 'Flash', 'AuthService', 'testParams', '$stateParams', '$cookies', 'NgTableParams', function($scope, $rootScope, $state, $route, GetData, $http, $routeParams, $window, Flash, AuthService, testParams, $stateParams, $cookies, NgTableParams){
	
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	if($scope.isAuthenticated == true){
		$scope.user = $rootScope.currentUser;
		$scope.state = $state.$current.name;
		$http.get(site+'/plans/GetPlan/' + $stateParams.planId)
		.then(function(response){
			console.log($scope);
			console.log(response.data);
			var data = response.data;
			$scope.plans = [data];
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
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$state.go('home');
	}
	function getTest($test){
		$http.get(site + '/plans/GetTest/' + $test.test_id)
			.then(function(response) {
			console.log(response.data);
			if($.isEmptyObject(response.data)){
				$test.errors = response.data.errors;
			}else{
				$test.errors = response.data.errors;
				$test.sweeps = response.data.sweeps;
			}
		});
		return $test;
	}
$scope.getTestData = function($test){
		if(!$test.isOpen && $test.dirty)
			return;
		else{
			$test.dirty = true;
		}
		if(!$test.sweeps){			
			$test = getTest($test);
		}
		console.log($test);
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
	
//	$scope.filterByName = function(params, word){
//		var data = params.filter(param => param.param_name.includes(word));
//		if(typeof data[0] != 'undefined'){
//			return true;
//		} else{
//			return false;
//		}
//	};
	
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