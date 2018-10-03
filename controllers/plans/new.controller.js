myApp.controller('newPlanCtrl', ['$scope', '$timeout', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', 'FileSaver', 'Blob', function ($scope, $timeout, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams, fileSaver, Blob) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;
//	$scope.checkLineup = false;
	var site = testParams.site;
//	var $select = $scope.$select
	$scope.testStructs = $scope.testParams.structs;
//	console.log($scope.testStructs);

	if($scope.isAuthenticated == false){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
		$window.location.reload();
	};
	
	$scope.array = [];
	$scope.plan = {};
	$scope.chipPairs = [{}];
	$scope.plan.userId = $cookies.getObject('loggedUser').id;
	$scope.plan.username = $cookies.getObject('loggedUser').username;
	$scope.fileData = {};
	
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.insertTest = function(){
		this.test.scopeIndex = this.$id;
		$scope.planParams.push(this.test);
		$scope.lock = true;
		console.log(this.test);
	}
	$scope.editToggle = function(){
		$scope.lock = false;
		var index = $scope.planParams.findIndex(test => test.scopeIndex == this.$id);
		$scope.planParams.splice(index, 1);
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
	$scope.calc = false;
	$scope.showCalc = function(){
		$scope.calc = !$scope.calc;
	};
	
	$scope.addPair = function(){
		$scope.chipPairs.push({});
	}
	
	$scope.extras = function(test, sweep){
		console.log(sweep);
		console.log(test);
		var sweepName = sweep.name;
		if(!test.sweeps){
			test.sweeps = [];
		}
		if(!test.sweeps[sweepName]){
			test.sweeps[sweepName] = {};
		}
		if(!test.sweeps[sweepName].data){
			test.sweeps[sweepName].data = [];
		}			
		if(!test.sweeps[sweepName].data.ext){
			test.sweeps[sweepName].data.ext = [];
		}else{
			test.sweeps[sweepName].data.ext = null;
		}
		console.log(test);
	}
	
	$scope.open = function(index, id, sweepName){
		var sweep = this.test.sweeps[sweepName];
		var buttonID = id.toString() + index.toString();
		var button = document.getElementById(buttonID);
		button.click();
		setTimeout(function(){
			var path = jQuery(button).val();
			var name = path.split('\\').pop();
			console.log(name);
			console.log(sweep);
			
		}, 2500);
		var station = this.test.station[0].name;
		var test = this.test.testType[0].test_name;
		var url = site+"/uploads/"+station+"/"+test;
		sweep.path = url;
	}
	
	$scope.uploadFile = function(sweep){
//		var station = this.test.station[0].name;
//		var test = this.test.testType[0].test_name;
//		var path = "uploads/"+station+"/"+test;
//		sweep.path = path;
//		var file = sweep.data
//		var uploadUrl = site+'/plans/upload';
//		var text = sweep.data.name;
		console.log($scope.fileData);
//		console.log(sweep);
//		var data = new Blob([sweep.data], { type: 'application/vnd.ms-excel' });
//		var reader = new FileReader();
//		console.log(data);
//		reader.addEventListener("loadend", function() {
//			 // reader.result contains the contents of blob as a typed array
//			console.log(reader.result);
//		});
//		var text = reader.readAsText(data);
//		console.log(text);
//		
//		fileUpload.uploadFileToUrl(file, uploadUrl, text, path);
   };
	
	$scope.selectAll = function(test, sweep){
		console.log(test);
		console.log(sweep);
		var result = testParams.params.allParams.filter(item => item.config_id == sweep.config_id);
		console.log(result);
		if(!test.sweeps){
			test.sweeps = [];
		}
		test.sweeps[sweep.name].data = result;
	};

	$scope.addPlan = function() {
		$http.post(site+'/plans/createnew', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			Flash.clear();
			console.log(response.data);
			if(typeof response.data == "object"){
				response.data.forEach(function(error){
					var message = "<strong>" + error.source +"</strong>" + ": " + error.msg;
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
//				$location.path('/plans');
			}
		})
//		console.log($scope.array);
	};
	
	
	$scope.copyTest = function(){
		$http.post(site+'/plans/copyTest', $scope.copyId)
		.then(function(response){
			console.log(response.data);
			$scope.test = response.data;
		})
//		console.log($scope.copyId);
	}
}]);