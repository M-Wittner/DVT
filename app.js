var underscore = angular.module('underscore', []);
underscore.factory('_', ['$window', function ($window) {
	return $window._; // assumes underscore has already been loaded on the page
}]);

var myApp = angular.module('myApp', ['ui.router', 'ngRoute', 'ngTable', 'ui.bootstrap', 'ngFlash', 'ngCookies', 'trumbowyg-ng', 'ui.select', 'underscore']);

//myApp.config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {
//	$httpProvider.defaults.cache = false;
//	if (!$httpProvider.defaults.headers.get) {
//		$httpProvider.defaults.headers.get = {};
//	}
//	// disable IE ajax request caching
//	$httpProvider.defaults.headers.get['If-Modified-Since'] = '0';
//	//.... proceed routes
//	$routeProvider
//		.when('/', {
//			templateUrl: 'pages/home.html',
//			controller: 'homeCtrl'
//		})
//		.when('/register', {
//			templateUrl: 'pages/register.html',
//			controller: 'regCtrl'
//		})
//		//		--------------------	DVT PAGES --------------------
//		.when('/plans', {
//			templateUrl: 'pages/plans/index.html',
//			controller: 'plansCtrl'
//		})
//		.when('/plans/new', {
//			templateUrl: 'pages/plans/new.html',
//			controller: 'newPlanCtrl'
//		})
//		.when('/plans/:id', {
//			templateUrl: 'pages/plans/view.html',
//			controller: 'viewPlanCtrl'
//		})
//		.when('/plans/:planId/test/:testId/edit', {
//			templateUrl: 'pages/plans/edit.html',
//			controller: 'editPlanCtrl'
//		})
//		.when('/plans/:planId/test/:testId/comments/new', {
//			templateUrl: 'pages/comments/new.html',
//			controller: 'newCommentCtrl'
//		})
//		.when('/plans/:planId/test/:testId/comment/:commentId/edit', {
//			templateUrl: 'pages/comments/edit.html',
//			controller: 'editCommentCtrl'
//		})
//		.when('/plans/:planId/addtests', {
//			templateUrl: 'pages/plans/addtest.html',
//			controller: 'addTestCtrl'
//		})
//		//		--------------------	TASKS PAGES --------------------
//		.when('/tasks', {
//			templateUrl: 'pages/tasks/index.html',
//			controller: 'tasksCtrl'
//		})
//		.when('/tasks/new', {
//			templateUrl: 'pages/tasks/new.html',
//			controller: 'newTaskCtrl'
//		})
//		.when('/tasks/:id', {
//			templateUrl: 'pages/tasks/view.html',
//			controller: 'viewTaskCtrl'
//		})
//		.when('/tasks/:id/edit', {
//			templateUrl: 'pages/tasks/edit.html',
//			controller: 'editTaskCtrl'
//		})
//		.when('/tasks/:id/comment/new', {
//			templateUrl: 'pages/tasks/comments/new.html',
//			controller: 'newTaskCommentCtrl'
//		})
//		//		--------------------	LINEUP PAGES --------------------
//		.when('/lineups', {
//			templateUrl: 'pages/lineups/index.html',
//			controller: 'lineupsCtrl'
//		})
//		.when('/lineups/new', {
//			templateUrl: 'pages/lineups/new.html',
//			controller: 'newLineupCtrl'
//		})
//		.when('/lineups/check', {
//			templateUrl: 'pages/lineups/check.html',
//			controller: 'checkLineupCtrl'
//		})
//		//		--------------------	PROFILE PAGES --------------------
//		.when('/:username/tasks', {
//			templateUrl: 'pages/profile/myTasks.html',
//			controller: 'myTasksCtrl'
//		})
//		//		--------------------	Operators PAGES --------------------
//		.when('/operators/calendar', {
//			templateUrl: 'pages/operators/calendar.html',
//			controller: 'calendarCtrl'
//		})
//		//		--------------------	ADMIN PAGES --------------------
//		.when('/admin', {
//			templateUrl: 'pages/admin/panel.html',
//			controller: 'adminCtrl'
//		})
//		.when('/admin/search', {
//			templateUrl: 'pages/admin/search.html',
//			controller: 'searchCtrl'
//		})
//		.when('/admin/newchip', {
//			templateUrl: 'pages/admin/newchip.html',
//			controller: 'chipCtrl'
//		})
//		.when('/admin/newtest', {
//			templateUrl: 'pages/admin/newtest.html',
//			controller: 'testCtrl'
//		})
//		.when('/admin/newstation', {
//			templateUrl: 'pages/admin/newstation.html',
//			controller: 'stationCtrl'
//		})
//		.when('/admin/editstation/:station', {
//			templateUrl: 'pages/admin/editstation.html',
//			controller: 'editStationCtrl'
//		})
//		.when('/admin/chiplist', {
//			templateUrl: 'pages/admin/chiplist.html',
//			controller: 'chiplistCtrl'
//		})
//		.when('/admin/testlist', {
//			templateUrl: 'pages/admin/testlist.html',
//			controller: 'testlistCtrl'
//		})
//		.when('/admin/stationlist', {
//			templateUrl: 'pages/admin/stationlist.html',
//			controller: 'stationlistCtrl'
//		})
//		.when('/admin/iterationlist', {
//			templateUrl: 'pages/admin/iterationlist.html',
//			controller: 'iterationlistCtrl'
//		})
//		.when('/admin/edititeration/:id', {
//			templateUrl: 'pages/admin/edititeration.html',
//			controller: 'edititerationCtrl'
//		})
//		.when('/admin/operations', {
//			templateUrl: 'pages/admin/operations.html',
//			controller: 'operationsCtrl'
//		})
//	//		.otherwise({redirectTo: '/'});
//
//	$locationProvider.hashPrefix('');
//
//}]);

myApp.config(['$stateProvider', '$locationProvider', '$urlRouterProvider', function ($stateProvider, $locationProvider, $urlRouterProvider) {
	$stateProvider
		.state('home' , {
			name: 'home',
			url: '/',
			templateUrl: 'pages/home.html',
			controller: 'homeCtrl'
		})
		.state('register', {
			name: 'register',
			url: '/register',
			templateUrl: 'pages/register.html',
			controller: 'regCtrl'
		})
//		--------------------	DVT PAGES --------------------
		.state('todayPlan', {
			name: 'todayPlan',
			url: '/today',
			templateUrl: 'pages/plans/view.html',
			controller: 'todayPlanCtrl',
		})
		.state('plans', {
			name: 'plans',
			url: '/plans',
			templateUrl: 'pages/plans/index.html',
			controller: 'plansCtrl',
		})
		.state('newPlan', {
			name: 'newPlan',
			url: '/new',
			templateUrl: 'pages/plans/new.html',
			controller: 'newPlanCtrl'
		})
		.state('viewPlan', {
			name: 'viewPlan',
			url: '/plans/{planId}',
			params: {},
			templateUrl: 'pages/plans/view.html',
			controller: 'viewPlanCtrl',
//			resolve: {
//				'planData': ['$state', '$stateParams', 'GetData', function($state, $stateParams, GetData){
//					return GetData.getPlan({id: $stateParams.planId});
//				}]
//			}
		})
		.state('viewTest', {
			name: 'viewTest',
			url: '/plans/{planId}/test/{testId}',
			params: {
				obj: null,
				test: null
			},
			templateUrl: 'pages/plans/viewTest.html',
			controller: 'viewTestCtrl'
		})
		.state('editTest', {
			name: 'editTest',
			url: '/plans/{planId}/test/{testId}/edit',
			params: {
				obj: null
			},
			templateUrl: 'pages/plans/edit.html',
			controller: 'editPlanCtrl'
		})
		.state('addTest', {
			name: 'addTest',
			url: '/plans/{planId}/addtests',
			params: {
				obj: null
			},
			templateUrl: 'pages/plans/addtest.html',
			controller: 'addTestCtrl'
		})
		.state('editComment', {
			name: 'editComment',
			url: '/plans/{planId}/test/{testId}/comment/{commentId}/edit',
			params: {
				obj: null
			},
			templateUrl: 'pages/comments/edit.html',
			controller: 'editCommentCtrl'
		})
//		--------------------	TASKS PAGES --------------------
		.state('tasks', {
			name: 'tasks',
			url: '/tasks',
			templateUrl: 'pages/tasks/index.html',
			controller: 'tasksCtrl'
		})
		.state('newTasks', {
			name: 'newTasks',
			url: '/tasks/new',
			templateUrl: 'pages/tasks/new.html',
			controller: 'newTaskCtrl'
		})
		.state('viewTask', {
			name: 'viewTask',
			url: '/tasks/{testId}',
			params: {
				obj: null
			},
			templateUrl: 'pages/tasks/view.html',
			controller: 'viewTaskCtrl'
		})
		.state('editTask', {
			name: 'editTask',
			url: '/tasks/{testId}/edit',
			params: {
				obj: null
			},
			templateUrl: 'pages/tasks/edit.html',
			controller: 'editTaskCtrl'
		})
		.state('commentTask', {
			name: 'commentTask',
			url: '/tasks/{testId}/edit',
			params: {
				obj: null
			},
			templateUrl: 'pages/tasks/edit.html',
			controller: 'editTaskCtrl'
		})
//		--------------------	ADMIN PAGES --------------------
		.state('log', {
			name: 'log',
			url: '/log',
			templateUrl: 'pages/admin/operations.html',
			controller: 'logCtrl',
		})
		.state('chipList', {
			name: 'chipList',
			url: '/chips/list',
			templateUrl: 'pages/admin/chiplist.html',
			controller: 'chipListCtrl',
		})
		.state('stations', {
			name: 'stations',
			url: '/stations',
			templateUrl: 'pages/admin/stations.html',
			controller: 'stationsCtrl',
		})
//		--------------------	PROFILE PAGES --------------------
		.state('userTasks', {
			name: 'userTasks',
			url: '/{username}/tasks',
			params: {
				obj: null
			},
			templateUrl: 'pages/profile/myTasks.html',
			controller: 'myTasksCtrl'
		});
	$locationProvider.hashPrefix('');
	$locationProvider.html5Mode();
}]);

//myApp.run(function ($animate) {
//	$animate.enabled(true);
//})

myApp.service('GetData', function($q, $http, $rootScope){
	var site = $rootScope.site;
	this.getPlan = function($plan){
		var deffered = $q.defer();
		$http.get(site + '/plans/GetPlan/' + $plan.id)
			.then(function(response) {
			deffered.resolve(response.data);
			if($.isEmptyObject(response.data) || response.data.errors.length > 0){
				$plan.errors = response.data.errors;
			}else{	
				$plan = response.data;
				$plan.tests = response.data.tests;
				$plan.progress = response.data.progress;
			}
			console.log($plan);
		});
		return $plan;
	}
	this.getTest = function($test){
		$http.get(site + '/plans/GetTest/' + $test.test_id)
			.then(function(response) {
//			console.log(response.data);
			if($.isEmptyObject(response.data)){
				$test.errors = response.data.errors;
			}else{
				$test.errors = response.data.errors;
				$test.sweeps = response.data.sweeps;
			}
		});
		return $test;
	}
	this.getTestData = function($test){
		if(!$test.isOpen && $test.dirty)
			return;
		else{
			$test.dirty = true;
		}
		if(!$test.sweeps){			
			$test = this.getTest($test);
		}
		console.log($test);
	}
//	return Data;
})

//		------------------------	New Test Directives ------------------------
myApp.directive('testForm', function () {
	return {
		templateUrl: 'pages/plans/newTest.html',
		controller: 'testFormCtrl',
		scope: {
			planParams: '=',
			params: '=',
			index: '&',
			locked: '=',
		},
		link: function ($scope, $elm) {

		}
	}
});
myApp.directive('testStruct', function () {
	return {
		templateUrl: 'pages/plans/partials/Test Form/testStruct.html',
	}
})
myApp.directive('lineupSweep', function () {
	return {
		templateUrl: 'pages/plans/partials/Test Form/lineupSweep.html',
	}
})
myApp.directive('chipSweep', function () {
	return {
		templateUrl: 'pages/plans/partials/Test Form/chipSweep.html',
	}
})
myApp.directive('pinSweep', function () {
	return {
		templateUrl: 'pages/plans/partials/Test Form/pinSweep.html',
	}
})
myApp.directive('dacDigSweep', function () {
	return {
		templateUrl: 'pages/plans/partials/Test Form/dacDigSweep.html',
	}
})
myApp.directive('generalSweep', function () {
	return {
		templateUrl: 'pages/plans/partials/Test Form/generalSweep.html',
	}
})
myApp.directive('singleSweep', function () {
	return {
		templateUrl: 'pages/plans/partials/Test Form/singleSweep.html',
	}
})
//		--------------------	End Of New Test Directives --------------------
//		------------------------	View Test Directives ------------------------
myApp.directive('plan', function () {
	return {
		templateUrl: 'pages/plans/partials/view/plan.html',
	}
})
myApp.directive('test', function () {
	return {
		templateUrl: 'pages/plans/partials/view/test.html',
	}
})
myApp.directive('testProgressBar', function () {
	return {
		templateUrl: 'pages/plans/partials/View/progressBar.html',
		scope: {
			data: '=',
		}
	}
})
myApp.directive('testBar', function () {
	return {
		templateUrl: 'pages/plans/partials/view/testBar.html',
	}
})
myApp.directive('testStructView', function () {
	return {
		templateUrl: 'pages/plans/partials/View/testStruct.html',
	}
})
myApp.directive('testFooter', function () {
	return {
		templateUrl: 'pages/plans/partials/View/testFooter.html',
	}
})
myApp.directive('testErrors', function () {
	return {
		templateUrl: 'pages/plans/partials/view/testErrors.html',
	}
})
myApp.directive('commentForm', function () {
	return {
		templateUrl: 'pages/plans/partials/commentForm.html',
	}
})
//		--------------------	End Of View Test Directives --------------------


myApp.directive('addChip', function () {
	return {
		templateUrl: 'pages/admin/newchip.html',
	}
})

app.directive("importSheetJs", function () {
	return {
		require: '?ngModel',
		link: function ($scope, $elm, attr, ngModel) {
			$elm.bind('change', function (event) {
				ngModel.$setViewValue(event.target.files[0]);
				$scope.$apply();
			});
		}
	};
});
//myApp.service('fileUpload', ['$http', function ($http) {
//	this.uploadFileToUrl = function (file, uploadUrl, name, path) {
//		var fd = new FormData();
//		fd.append('file', file);
//		fd.append('name', name);
//		fd.append('path', path);
//		$http.post(uploadUrl, fd, {
//				transformRequest: angular.identity,
//				headers: {
//					'Content-Type': undefined,
//					'Process-Data': false
//				}
//			})
//			.then(function (response) {
//				console.log("Success");
//				console.log(response.data);
//			});
//	}
// }]);
myApp.directive('fdInput', ['$timeout', function ($timeout) {
	return {
		link: function (scope, element, attrs) {
			element.on('change', function (evt) {
				alert(evt.target.files[0].name);
				alert($('#$index').val());
			});
		}
	}
}]);
myApp.directive('lineupForm', function () {
	return {
		templateUrl: 'pages/lineups/partials/lineupForm.html',
		controller: 'newLineupCtrl',
		scope: {
			array: '=',
			//			lineup: '=',
		},
		link: function (scope, element, attrs) {

		}
	}
});
myApp.directive('fullSystem', function () {
	return {
		require: '^^lineupForm',
		templateUrl: 'pages/lineups/partials/stations/Full System/fullSystem.html',
		//		controller: 'newLineupCtrl',
		//		scope: {
		//			array: '=',
		//			sec: '=',
		//		},
		link: function (scope, element, attrs) {

		}
	}
});
myApp.directive('talynA', function () {
	return {
		require: ['^^lineupForm'],
		templateUrl: 'pages/lineups/partials/stations/Full System/TalynA/TalynA.html',
		//		controller: 'newLineupCtrl',
		//		scope: {
		//			array: '=',
		//			sec: '=',
		//		},
		link: function (scope, element, attrs) {

		}
	}
});
myApp.directive('talynM', function () {
	return {
		require: ['^^lineupForm'],
		templateUrl: 'pages/lineups/partials/stations/Full System/TalynM/TalynM.html',
		//		controller: 'newLineupCtrl',
		//		scope: {
		//		},
		link: function (scope, element, attrs) {
			this.lineup = scope.lineup;
		}
	}
});
myApp.directive('testFormedit', function () {
	return {
		templateUrl: 'pages/plans/newTestEdit.html',
		controller: 'addTestCtrl',
		scope: {
			planParams: '=',
			params: '=',
			index: '&',
			locked: '='
		},
		link: function (scope, element, attrs) {

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

myApp.factory('AuthService', function (testParams, $http, Session, $cookies) {
	var authService = {};
	var site = testParams.site

	authService.login = function (credentials) {
		return $http.post(site + '/auth/login', {
				user: credentials
			})
			.then(function (res) {
				//			console.log(res.data);
				if (res.data.login = true) {
					Session.create(res.data.__ci_last_regenerate, res.data.userId, res.data.username, res.data.firstName, res.data.lastName, res.data.rank, res.data.email, res.data.group_id);
					return res.data;
				} else {
					console.log('Error! Not logged in');
				}
				console.log(res.data);
			});
	};

	authService.isAuthenticated = function () {
		if ($cookies.getObject('loggedUser')) {
			return true;
		} else {
			return false;
		}

	};

	authService.isAuthorized = function (authorizedRoles) {
		if (!angular.isArray(authorizedRoles)) {
			authorizedRoles = [authorizedRoles];
		}
		return (authService.isAuthenticated() && authorizedRoles.indexOf(Session.userRole) !== -1);
	};

	return authService;
});
myApp.factory('siteParams', function ($http, $log, $rootScope) {
	//	$rootScope.$site = "http://wigig-299";
	var siteParams = {}
	var site = $rootScope.site;
	siteParams.params = {};
});
myApp.factory('taskParams', function ($http, $log, $rootScope) {
	var site = $rootScope.site;
	var taskParams = {};
	taskParams.autoUsers = {};
	taskParams.approved = [{id: 0, title: 'Declined'},{id: 1, title: 'Approved'}];
	taskParams.reviewed = [{id: 0, title: 'Not'},{id: 1, title: 'Reviewed'}];
	$http.get(site + '/params/autoUsers')
		.then(function (response) {
			//		console.log(response.data);
			taskParams.autoUsers = response.data.obj;
			taskParams.autoUsersArr = response.data.arr;
			taskParams.assignees = response.data.obj.map(user=>{
				return {id: user.id, title: user.fname + " " + user.lname};
			})
		});
	$http.get(site + '/params/fields')
		.then(function (response) {
			taskParams.fieldList = response.data.obj;
			taskParams.fieldListArr = response.data.arr;
//					console.log(response.data);
			taskParams.stations = response.data.obj.map(station => {
				return {id: station.id, title: station.work_station};
			});
		});
	$http.get(site + '/params/taskTypes')
		.then(function (response) {
			taskParams.taskTypes = response.data.obj;
			taskParams.taskTypesArr = response.data.arr;
		});
	$http.get(site + '/params/taskStatus')
		.then(function (response) {
			taskParams.taskStatus = response.data.obj;
			taskParams.taskStatusArr = response.data.arr;
			taskParams.statuses = response.data.obj.map(status => {
				return {id: status.id, title: status.task_status};
			});
		});

	$http.get(site + '/params/taskPriority')
		.then(function (response) {
			taskParams.taskPriority = response.data.obj;
			taskParams.taskPriorityArr = response.data.arr;
			taskParams.priorities = response.data.obj.map(status => {
				return {id: status.id, title: status.task_priority};
			});
		});
	//	console.log(taskParams);
	return taskParams;
});
myApp.factory('date', function(){
	var date = {};
	date.today = new Date();
	date.parse = function(dateIn) {
		var dd = dateIn.getDate();
		var mm = dateIn.getMonth() + 1; //January is 0!
		var yyyy = dateIn.getFullYear();
		if (dd < 10) {
			dd = '0' + dd
		}
		if (mm < 10) {
			mm = '0' + mm
		}
		var parsedDate = dd + '/' + mm + '/' + yyyy;
		return parsedDate;
	}
	
	return date;
})
myApp.factory('testParams', function ($http, $log, $rootScope) {
	$rootScope.site = "http://wigig-299";
	var testParams = {};
	var site = $rootScope.site;
	var today = new Date();
	testParams.parseDate = function (date) {
		var dd = date.getDate();
		var mm = date.getMonth() + 1; //January is 0!
		var yyyy = date.getFullYear();
		if (dd < 10) {
			dd = '0' + dd
		}

		if (mm < 10) {
			mm = '0' + mm
		}
		var parsedDate = dd + '/' + mm + '/' + yyyy;
		return parsedDate;
	}
//	$rootScope.parse = testParams.parseDate;
//	testParams.today = today;

	testParams.params = {};
	testParams.params.priorityList = [
		{display_name: '1 - Highest', value: '1'},
		{display_name: '2', value: '2'},
		{display_name: '3', value: '3'},
		{display_name: '4', value: '4'},
		{display_name: '5', value: '5'},
		{display_name: '6', value: '6'},
		{display_name: '7 - Lowest', value: '7'},
	];

	testParams.plans = {};
//	$http.get(site + '/plans')
//		.then(function (response) {
//			var data = response.data;
//			testParams.plans.web = data.web;
//			testParams.plans.lab = data.lab;
//		});

	$http.get(site + '/params/structs')
		.then(function (response) {
			testParams.structs = response.data;
		})
	$http.get(site + '/admin/stationList')
		.then(function (response) {
			testParams.params.workStations = response.data;
			$rootScope.workStations = response.data;
		});

	$http.get(site + '/admin/operatorList')
		.then(function (response) {
			testParams.params.operatorList = response.data;
			//		console.log(response.data);
		});

	$http.get(site + '/params/allChips')
		.then(function (response) {
			testParams.params.allChips = response.data;
		})
	$http.get(site + '/params/allParams')
		.then(function (response) {
			testParams.params.allParams = response.data;
			//					console.log(response.data);
		})

	$http.get(site + '/params/testTypes')
		.then(function (response) {
			testParams.params.testTypes = response.data;
		});

	return testParams;
});

myApp.service('Session', function () {
	this.create = function (sessionId, userId, username, fisrtName, lastName, rank, email, groupId) {
		this.id = sessionId;
		this.userId = userId;
		this.username = username;
		this.fisrtName = fisrtName;
		this.lastName = lastName;
		this.rank = parseInt(rank);
		this.email = email;
		this.group_id = groupId;
	};

	this.destroy = function () {
		this.id = null;
		this.userId = null;
		this.userRole = null;
		this.fisrtName = null;
		this.lastName = null;
		this.rank = null;
		this.email = null;
		this.group_id = null;
	};
});
