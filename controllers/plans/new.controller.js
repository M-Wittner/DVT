myApp.controller('newPlanCtrl', ['$scope', '$http', '$location', 'Flash', 'Session', '$cookies', 'AuthService', '$window', 'testParams', function ($scope, $http, $location, Flash, Session, $cookies, AuthService, $window, testParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.testParams = testParams;

	if($scope.isAuthenticated == false){
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
		$window.location.reload();
	};
	
	$scope.array = [];
	$scope.plan = {};
	$scope.plan.userId = $cookies.getObject('loggedUser').userId;
	$scope.plan.username = $cookies.getObject('loggedUser').username;
	
	$scope.testCount = [{}];
	$scope.addTest = function(){
		$scope.testCount.push({})
	}
	
	$scope.insertTest = function(){
		var input = $scope.test;
//		console.log(input);
//		if(input.lineup && input.priority && input.station && input.name && input.chips && input.pinFrom && input.pinTo && input.Step && input.temp && input.channel && input.antenna && input.voltage){
//			console.log('yes')
//		} else {
//			var message = 'Please make sure all input fields are filled';
//			var id = Flash.create('danger', message, 3500);
//		}
		$scope.planParams.push($scope.test);
		$scope.lock = true;
	}
	$scope.editToggle = function(){
		$scope.lock = false;
		$scope.planParams.splice($scope.test, 1);
	}
	
	$scope.removeTest = function() {
		$scope.testCount.splice($scope.testCount.length-1,1);
	}
	$scope.calc = false;
	$scope.showCalc = function(){
		$scope.calc = !$scope.calc;
	};

	$scope.addPlan = function() {
		$http.post('http://wigig-584/plans/create', {plan: $scope.plan, test: $scope.array})
		.then(function(response){
			if(response.data == 'success'){
				var message = 'Plan Created Succesfully!';
				var id = Flash.create('success', message, 3500);
				$location.path('/plans');
//				console.log(response.data)
			} else {
				var message = response.data;
				var id = Flash.create('danger', message, 3500);
				console.log(response.data);
			}
		})
//		console.log(this);
	};
	
	$('#noteBox').trumbowyg({
		btns:[
			['undo', 'redo'],		
			['strong', 'em', 'del'], ['link'],
			['superscript', 'subscript'],
			['foreColor', 'backColor'],
			['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
        	['unorderedList', 'orderedList'],
//			['upload'],
		],
//		plugins: {
//			upload: {
//				serverpath: 'http://wigig-584/plans/create',
//				fileFieldName: '.png',
////				urlPropertyName: {url: 			'https://example.com/myimage.jpg', success: true},
//				
//			}
//		}
	});
}]);