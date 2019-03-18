myApp.controller('plansCtrl', ['$scope', '$rootScope', '$filter', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', '$stateParams', '$state', function ($scope, $rootScope, $filter, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams, $stateParams, $state) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = $rootScope.site;
	function getPlan($plan){
//		console.log($plan.id)
		$http.get(site + '/plans/GetPlan/' + $plan.id)
			.then(function(response) {
			console.log(response.data);
			if($.isEmptyObject(response.data) || response.data.errors.length > 0){
				$plan.errors = response.data.errors;
			}else{
				for(var key in response.data){
					$plan[key] = response.data[key];
				}
			}
		});
		return $plan;
	}
	function getTest($test){
		$http.get(site + '/plans/GetTest/' + $test.test_id)
			.then(function(response) {
			if($.isEmptyObject(response.data)){
				$test.errors = response.data.errors;
			}else{
				for(var key in response.data){
					$test[key] = response.data[key];
				}
			}
		});
		return $test;
	}
	
	if($scope.isAuthenticated) {
//		console.log(testParams.plans);
		$scope.state = $state.$current.name;
		$scope.plans = testParams.plans;
		$scope.itemsPerPage = 15;
		$scope.currentPage = {
			web: 1,
			lab: 1
		};
		$scope.setPage = function(page){
			if(page > 0){
				var pageData = $scope.plans.lab.slice(
				(page - 1) * $scope.itemsPerPage,
				 page * $scope.itemsPerPage
				);
				$scope.labPlans = pageData;
			}
		}
		setTimeout(function(){$scope.setPage(1)}, 900);
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
	
	$scope.getPlanData = function($plan){
		if(!$plan.isOpen && $plan.dirty)
			return;
		else{
			$plan.dirty = true;
		}
		if(!$plan.tests){			
			$plan = getPlan($plan);
		}
//		console.log($plan);
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
//		console.log($test);
	}
	$scope.selected = [];
	$scope.deleteSelected = function(){
		if($scope.selected.length > 0){
			$http.post(site+'/plans/deletePlans', $scope.selected)
			.then(function(response){
//				console.log(response.data);
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
	
	$scope.deleteTest = function(testID, $plan){
		$http.get(site+'/plans/deleteTest/'+testID)
		.then(function(response){
			console.log(response.data);
			var res = response.data;
			var type = res.occured == true ? 'danger' : 'success';
			var msg = "Test #"+res.source+ " "+res.msg;
			var id = Flash.create(type, msg, 6000);
			console.log(type);
		}).then(function(){
//			$plan = getPlan($plan);
			$plan.tests = $plan.tests.filter(function(test){
				return test.test_id !== testID;
			})
		})
	}
	
	$scope.chipStatus = function(chip, flag, test, plan){
		chip.flag = flag == '' ? null : flag;
		console.log(chip);
		$http.post(site+'/plans/chipstatus', {chip: chip, user: $scope.user})
		.then(function(response){
			console.log(response.data);
			var keys = Object.keys(response.data);
			var key = keys[0];
			var username = keys[1];
			chip[key] = response.data[key];
			chip.username = response.data[username];
//			test.progress = response.data['progress'];
			var message = 'Chip ' + chip.chip_sn+'-'+chip.chip_process_abb + ' '+ key + ' has been updated <strong>(test: #' + chip.test_id +')</strong>';
			var id = Flash.create('success', message, 6000);
		}).then(function(){
//					var $test = getTest(test);
//			var $plan = getPlan(plan);
//					console.log($plan);
//			test = $test;
//					console.log('bal')
//				}).then(function(){
//			test.isOpen = true;
		});
	};;
	
	$scope.newCmt = function(testId, planId){
		var test = this.test;
		var comment = this.comment
		comment.test_id = testId;
		comment.plan_id = planId;
		comment.user_id = $scope.currentUser.id;
		$http.post(site+'/plans/addComment', comment)
		.then(function(response){
			if(response.data.comment == comment.text) {
				$window.scrollTo(0, 0);
				var message = 'Comment Was Added successfully';
				var id = Flash.create('success', message, 5000);
				console.log(response.data);
				test.comments.push(response.data);
				angular.element('#Comment'+testId).click();
			} else {
				console.log(response.data);
				var message = response.data;
				var id = Flash.create('danger', message, 5000);
			}
		})
//		console.log(this.comment);
	}
}]);