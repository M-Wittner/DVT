var myApp = angular.module('myApp', ['ngRoute', 'ngTable', 'ngAnimate', 'ngTouch', 'ui.bootstrap', 'btorfs.multiselect', 'ngFlash', 'ngFileSaver', 'ngCookies', 'trumbowyg-ng', 'ui.select', 'ngSanitize']);

myApp.config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {
	$httpProvider.defaults.cache = false;
    if (!$httpProvider.defaults.headers.get) {
      $httpProvider.defaults.headers.get = {};
    }
    // disable IE ajax request caching
    $httpProvider.defaults.headers.get['If-Modified-Since'] = '0';
    //.... proceed routes
	$routeProvider
		.when('/', {
		templateUrl: 'pages/home.html',
		controller: 'homeCtrl'
	})
		.when('/register', {
		templateUrl: 'pages/register.html',
		controller: 'regCtrl'
	})
//		--------------------	DVT PAGES --------------------
		.when('/plans', {
			templateUrl: 'pages/plans/index.html',
			controller: 'plansCtrl'
	})
		.when('/plans/new', {
			templateUrl: 'pages/plans/new.html',
			controller: 'newPlanCtrl'
	})
		.when('/plans/:id', {
			templateUrl: 'pages/plans/view.html',
			controller: 'viewPlanCtrl'
	})
		.when('/plans/:planId/test/:testId/edit', {
			templateUrl: 'pages/plans/edit.html',
			controller: 'editPlanCtrl'
	})
		.when('/plans/:planId/test/:testId/comments/new', {
			templateUrl: 'pages/comments/new.html',
			controller: 'newCommentCtrl'
	})
		.when('/plans/:planId/test/:testId/comment/:commentId/edit', {
			templateUrl: 'pages/comments/edit.html',
			controller: 'editCommentCtrl'
	})
		.when('/plans/:planId/addtests', {
			templateUrl: 'pages/plans/addtest.html',
			controller: 'addTestCtrl'
	})
//		--------------------	TASKS PAGES --------------------
		.when('/tasks', {
			templateUrl: 'pages/tasks/index.html',
			controller: 'tasksCtrl'
	})
		.when('/tasks/new', {
			templateUrl: 'pages/tasks/new.html',
			controller: 'newTaskCtrl'
	})
		.when('/tasks/:id', {
			templateUrl: 'pages/tasks/view.html',
			controller: 'viewTaskCtrl'
	})
		.when('/tasks/:id/edit', {
			templateUrl: 'pages/tasks/edit.html',
			controller: 'editTaskCtrl'
	})
		.when('/tasks/:id/comment/new', {
			templateUrl: 'pages/tasks/comments/new.html',
			controller: 'newTaskCommentCtrl'
	})
	//		--------------------	LINEUP PAGES --------------------
		.when('/lineups', {
			templateUrl: 'pages/lineups/index.html',
			controller: 'lineupsCtrl'
	})
		.when('/lineups/new', {
			templateUrl: 'pages/lineups/new.html',
			controller: 'newLineupCtrl'
	})
//		--------------------	ADMIN PAGES --------------------
		.when('/admin', {
			templateUrl: 'pages/admin/panel.html',
			controller: 'adminCtrl'
	})
		.when('/admin/search', {
			templateUrl: 'pages/admin/search.html',
			controller: 'searchCtrl'
	})
		.when('/admin/newchip', {
			templateUrl: 'pages/admin/newchip.html',
			controller: 'chipCtrl'
	})
		.when('/admin/newtest', {
			templateUrl: 'pages/admin/newtest.html',
			controller: 'testCtrl'
	})
		.when('/admin/newstation', {
			templateUrl: 'pages/admin/newstation.html',
			controller: 'stationCtrl'
	})
		.when('/admin/editstation/:station', {
			templateUrl: 'pages/admin/editstation.html',
			controller: 'editStationCtrl'
	})
		.when('/admin/chiplist', {
			templateUrl: 'pages/admin/chiplist.html',
			controller: 'chiplistCtrl'
	})
		.when('/admin/testlist', {
			templateUrl: 'pages/admin/testlist.html',
			controller: 'testlistCtrl'
	})
		.when('/admin/stationlist', {
			templateUrl: 'pages/admin/stationlist.html',
			controller: 'stationlistCtrl'
	})
		.when('/admin/iterationlist', {
			templateUrl: 'pages/admin/iterationlist.html',
			controller: 'iterationlistCtrl'
	})
		.when('/admin/edititeration/:id', {
			templateUrl: 'pages/admin/edititeration.html',
			controller: 'edititerationCtrl'
	})
		.when('/admin/operations', {
			templateUrl: 'pages/admin/operations.html',
			controller: 'operationsCtrl'
	})
//		--------------------	Robot PAGES --------------------
		.when('/robot', {
			templateUrl: 'pages/robot/index.html',
			controller: 'robotCtrl'
	})
		.when('/robot/new', {
			templateUrl: 'pages/robot/new.html',
			controller: 'newRobotPlanCtrl'
	})
		.when('/robot/:id', {
			templateUrl: 'pages/robot/view.html',
			controller: 'viewRobotPlanCtrl'
	})
		.when('/robot/:id/addtests', {
			templateUrl: 'pages/robot/addTest.html',
			controller: 'addTestsRobotCtrl'
	})
		.when('/robot/:planId/test/:testId/edit', {
			templateUrl: 'pages/robot/edit.html',
			controller: 'editRobotPlanCtrl'
	})
//		.when('/robot/:planId/test/:testId/comments/new', {
//			templateUrl: 'pages/robotnts/new.html',
//			controller: 'newCommentCtrl'
//	})
		.otherwise({redirectTo: '/'});

	$locationProvider.hashPrefix('');

}]);

myApp.run(function($animate) {
  $animate.enabled(true);
})

myApp.directive('testForm', function(){
	return {
		templateUrl: 'pages/plans/newTest.html',
//		controller: 'testCtrl',
		scope: {
			planParams: '=',
			params: '=',
			index: '&',
			locked: '='
		},
		link: function(scope, element, attrs){
			
		}
	}
});

myApp.directive('lineupForm', function(){
	return {
		templateUrl: 'pages/lineups/partials/lineupForm.html',
		controller: 'newLineupCtrl',
		scope: {
			array: '=',
//			lineup: '=',
		},
		link: function(scope, element, attrs){
			
		}
	}
});

myApp.directive('robotTestform', function(){
	return {
		templateUrl: 'pages/robot/robotTestform.html',
		controller: 'newRobotPlanCtrl',
		scope: {
			planParams: '=',
			test: '=',
			params: '=',
			index: '&',
			locked: '='
		},
		link: function(scope, element, attrs){
			
		}
	}
});

myApp.directive('testFormedit', function(){
	return {
		templateUrl: 'pages/plans/newTestEdit.html',
		controller: 'addTestCtrl',
		scope: {
			planParams: '=',
			params: '=',
			index: '&',
			locked: '='
		},
		link: function(scope, element, attrs){
			
		}
	}
});
myApp.directive('robotTestformadd', function(){
	return {
		templateUrl: 'pages/robot/robotTestFormAdd.html',
		controller: 'addTestsRobotCtrl',
		scope: {
			planParams: '=',
			test: '=',
			params: '=',
			index: '&',
			locked: '='
		},
		link: function(scope, element, attrs){
			
		}
	}
});

myApp.constant('AUTH_EVENTS', {
  loginSuccess: 'auth-login-success',
  loginFailed: 'auth-login-failed',
  logoutSuccess: 'auth-logout-success',
  sessionTimeout: 'auth-session-timeout',
  notAuthenticated: 'auth-not-authenticated',
  notAuthorized: 'auth-not-authorized'
});

myApp.constant('USER_ROLES', {
  all: '*',
  admin: 'admin',
  editor: 'editor',
  guest: 'guest'
});

myApp.factory('LS', function($window, $rootScope){
		var LS = {};
		LS.setData = function(val){
			$window.localStorage && $window.localStorage.setItem('chipStatus', val);
			return this;
		};
		LS.getData = function(){
			return $window.localStorage && $window.localStorage.getItem('chipStatus');
		};
	return LS;
});

myApp.factory('AuthService', function($http, Session, $cookies){
	var authService = {};
	
	authService.login = function (credentials){
		return $http.post('http://wigig-584/auth/login', {user: credentials})
		.then(function(res){
			if(res.data.login = true){
				Session.create(res.data.__ci_last_regenerate, res.data.userId, res.data.username, res.data.firstName, res.data.lastName, res.data.rank);
				return res.data;	
			} else {
				console.log('Error! Not logged in');
			}
			console.log(res.data);
		});
	};
	
	authService.isAuthenticated = function(){
		if($cookies.getObject('loggedUser')){
			return true;
		} else {
			return false;
		}
			
		};
		
		authService.isAuthorized = function(authorizedRoles){
			if(!angular.isArray(authorizedRoles)){
				authorizedRoles = [authorizedRoles];
			}
			return(authService.isAuthenticated() && authorizedRoles.indexOf(Session.userRole) !== -1);
		};
		
		return authService;
});

myApp.factory('testParams', function($http, $log){
	var testParams = {};
	testParams.site = "http://localhost";
	var site = testParams.site;
	testParams.params = {};
	testParams.lineups = {};
	
	$http.get(site+'/params/lineupParams')
	.then(function(response){
		testParams.lineups = response.data;
		console.log(response.data);
	})
	
	$http.get(site+'/params/fields')
	.then(function(response){
		testParams.params.fieldList = response.data.obj;
		testParams.params.fieldListArr = response.data.arr;
//		console.log(response.data);
	});
	$http.get(site+'/params/taskTypes')
	.then(function(response){
		testParams.params.taskTypes = response.data.obj;
		testParams.params.taskTypesArr = response.data.arr;
	});
	$http.get(site+'/params/taskStatus')
	.then(function(response){
		testParams.params.taskStatus = response.data.obj;
		testParams.params.taskStatusArr = response.data.arr;
	});
	
	$http.get(site+'/params/taskPriority')
	.then(function(response){
		testParams.params.taskPriority = response.data.obj;
		testParams.params.taskPriorityArr = response.data.arr;
	});

	$http.get(site+'/params/autoUsers')
	.then(function(response){
		testParams.params.autoUsers = response.data.obj;
		testParams.params.autoUsersArr = response.data.arr;
	});
	
	$http.get(site+'/params/stations')
	.then(function(response){
		testParams.params.stationList = response.data;
	});
	
	testParams.params.newTest = [
		'R - Stations',
		'M - Stations',
		'Calibration',
		'TalynM+A',
		'Robot',
		'PTAT/ABS/Vgb+TEMP',
	];
	
//	testParams.params.nameListM = {};
	$http.get(site+'/params/testsM')
	.then(function(response){
		testParams.params.nameListM = response.data;
	});
	
	testParams.params.nameListR = {};
	$http.get(site+'/params/testsR')
	.then(function(response){
		testParams.params.nameListR = response.data;
//			console.log(response.data);
	});
	
	testParams.params.nameListCal = {};
	$http.get(site+'/params/testsCal')
	.then(function(response){
		testParams.params.nameListCal = response.data;
//			console.log(response.data);
	});
	
	testParams.params.nameListPTAT = {};
	$http.get(site+'/params/testsPTAT')
	.then(function(response){
		testParams.params.nameListPTAT = response.data;
//			console.log(response.data);
	});
	testParams.params.nameListFS = {};
	$http.get(site+'/params/testsFS')
	.then(function(response){
		testParams.params.nameListFS = response.data;
//			console.log(response.data);
	});
	
//	testParams.params.nameListRobot = {};
	$http.get(site+'/params/testsRobot')
	.then(function(response){
		testParams.params.nameListRobot = response.data;
//			console.log(response.data);
	});
	
	$http.get(site+'/params/modulesRobot')
	.then(function(response){
		testParams.params.moduleListRobot = response.data;
//			console.log(response.data);
	});
	
	testParams.params.robotChList = [
		'1',
		'2',
		'3',
		'4',
		'5',
	];	
	
	testParams.params.robotGainIdxList = [
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
		'8',
		'9',
		'10',
		'11',
		'12',
		'13',
		'14',
		'15',
	];
	testParams.params.gainTableIdx = ['1','2','3','4','5','6','7','8','9','10','11','12','13','14','15'];
	
	testParams.params.xifList = {};
	$http.get(site+'/params/xifs')
	.then(function(response){
		testParams.params.xifList = response.data;
	});
	
	testParams.params.chipListM = {};
    $http.get(site+'/params/chipsM')
	.then(function(response){
		testParams.params.chipListM = response.data;
	});
	
	testParams.params.chipListR = {};
	$http.get(site+'/params/chipsR')
	.then(function(response){
		testParams.params.chipListR = response.data;
//		console.log(response.data);
	});
		
	testParams.params.chipListMR = {};
	$http.get(site+'/params/chipsMR')
	.then(function(response){
		testParams.params.chipListMR = response.data;
//		console.log(response.data);
	});
	
	testParams.params.tempList = [
		'-40',
		'-30',
		'-20',
		'-10',
		'0',
		'10',
		'20',
		'25',
		'30',
		'40',
		'50',
		'60',
		'70',
		'80',
		'85',
		'90',
		'95',
		'100',
		'105',
	];
	
	testParams.params.priorityList = [
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
	];
	
	testParams.params.chList1 = [
		'1',
		'2',
		'3',
		'4',
		'5',
		'7',
		'8',
		'9',
		'10'
	];
	
	testParams.params.antList = [
		'0',
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
		'8',
		'9',
		'10',
		'11',
		'12',
		'13',
		'14',
		'15',
		'16',
		'17',
		'18',
		'19',
		'20',
		'21',
		'22',
		'23',
		'24',
		'25',
		'26',
		'27',
		'28',
		'29',
		'30',
		'31',
	];

	testParams.status = {
		isopen: false
	};

	testParams.toggled = function (open) {
		$log.log('Dropdown is now: ', open);
	};

	testParams.toggleDropdown = function ($event) {
		$event.preventDefault();
		$event.stopPropagation();
		testParams.status.isopen = !testParams.status.isopen;
	};

	testParams.appendToEl = angular.element(document.querySelector('#dropdown-long-content'));
	
	return testParams;
});

myApp.filter('unique', function() {
   return function(collection, keyname) {
      var output = [], 
          keys = [];

      angular.forEach(collection, function(item) {
          var key = item[keyname];
          if(keys.indexOf(key) === -1) {
              keys.push(key);
              output.push(item);
          }
      });

      return output;
   };
});

myApp.service('Session', function(){
	this.create = function(sessionId, userId, username, fisrtName, lastName, rank){
		this.id = sessionId;
		this.userId = userId;
		this.username = username;
		this.fisrtName = fisrtName;
		this.lastName = lastName;
		this.rank = parseInt(rank);
	};
	
	this.destroy = function(){
		this.id = null;
		this.userId = null;
		this.userRole = null;
		this.fisrtName = null;
		this.lastName = null;
		this.rank = null;
	};
});