myApp.controller('todayPlanCtrl', ['$rootScope', '$scope', '$state', '$route', '$filter', '$location', '$http', '$stateParams', '$window', '$interval', 'Flash', 'AuthService', '$cookies', 'NgTableParams', function($rootScope, $scope, $state, $route, $filter, $location, $http, $stateParams, $window, $interval, Flash, AuthService, $cookies, NgTableParams){
	
	$scope.root = $rootScope;
	$scope.isAuthenticated = AuthService.isAuthenticated();
//	console.log($rootScope);
	var site = $rootScope.site;
//	$interval(function(){console.log('bla')}, 1000, 0 , true);
	if($scope.isAuthenticated == true){
		$scope.currentUser =$cookies.getObject('loggedUser');
		$scope.state = $state.$current.name;
		var today = new Date();
		$scope.updateTime = today.toLocaleTimeString('he-IL', {hour12: false});
		$scope.plans = [];
		if($scope.plans.length == 0){
			$http.post(site+'/plans/today', today)
				.then(function(response){
					$scope.today = $scope.root.parse(today);
	//				console.log(response.data[0]);
					console.log(response.data);
					$scope.plans = response.data;
					$scope.plans[0].isOpen = true;
				})
			$interval(function(){$http.post(site+'/plans/today', today)
				.then(function(response){
					$scope.today = $scope.root.parse(today);
	//				console.log(response.data[0]);
					$scope.updateTime = new Date().toLocaleTimeString('he-IL', {hour12: false});
					console.log("Data Refreshed, "+ $scope.updateTime);
					$scope.plans = response.data;
					$scope.plans[0].isOpen = true;
				})}, 300000, 0 , true);;
		}
//		$scope.tableErrors = new NgTableParams({});
		$scope.user = {};
		$scope.user.id = $cookies.getObject('loggedUser').id;
		$scope.user.username = $cookies.getObject('loggedUser').username;
		} else {
			var message = 'Please Login first!';
			var id = Flash.create('danger', message, 3500);
			$location.path('/');
		}
	
	function getPlan($plan){
		console.log($plan.id)
		$http.get(site + '/plans/GetPlan/' + $plan.id)
			.then(function(response) {
			if($.isEmptyObject(response.data) || response.data.errors.length > 0){
				$plan.errors = response.data.errors;
			}else{
				$plan.tests = response.data.tests;
				$plan.progress = response.data.progress;
			}
		});
		return $plan;
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

	$scope.getPlanData = function($plan){
		if(!$plan.isOpen && $plan.dirty)
			return;
		else{
			$plan.dirty = true;
		}
		if(!$plan.tests){			
			$plan = getPlan($plan);
		}
		console.log($plan);
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
	
	$scope.logger = function(data){
		console.log(data);
	};
	$scope.startTests = function($stations){
		var filteredStations = [];
		$stations.forEach(function(station, $i){
			if(station.hasOwnProperty('run') && station.run){
				 filteredStations.push(station);
			}
		})
		console.log(filteredStations);
		$http.post(site + '/plans/runTest/', filteredStations)
		.then(function(response){
			console.log(response.data);
			response.data.forEach(function(station, $i){
				if(station.errors.length > 0){
					station.errors.forEach(function(error, $i){
						var message = "<strong>"+station.name+":</strong> "+error.msg;
						var id = Flash.create('danger', message, 0);
					})
				}else{
						var message = station.name + " has been started!";
						var id = Flash.create('success', message);
				}
			})
		})
	}

		$scope.toggleTest = function(index){
			console.log(index);
			$scope.toggleCollapse = !$scope.toggleCollapse;
			setTimeout($scope.toggleFade = $scope.toggleFade, 1500);
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

		$scope.chipStatus = function(chip, flag, test){
			chip.flag = flag == '' ? null : flag;
			console.log(test);
			$http.post(site+'/plans/chipstatus', {chip: chip, user: $scope.user})
			.then(function(response){
				console.log(response.data);
				var keys = Object.keys(response.data);
				var key = keys[0];
				var username = keys[1];
				chip[key] = response.data[key];
				chip.username = response.data[username];
				test.progress = response.data['progress'];
				var message = 'Chip ' + chip.chip_sn+'-'+chip.chip_process_abb + ' '+ key + ' has been updated <strong>(test: #' + chip.test_id +')</strong>';
				var id = Flash.create('success', message, 6000);
			})
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