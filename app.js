var myApp = angular.module('myApp', ['ngRoute', 'ngTable', 'ngAnimate', 'ngTouch', 'ui.bootstrap', 'btorfs.multiselect', 'ngFlash', 'ngCookies', 'trumbowyg-ng', 'ui.select', 'ngSanitize', 'ui.calendar', 'angularFileUpload']);

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
		.when('/lineups/check', {
			templateUrl: 'pages/lineups/check.html',
			controller: 'checkLineupCtrl'
	})
//		--------------------	PROFILE PAGES --------------------
		.when('/:username/tasks', {
				templateUrl: 'pages/profile/myTasks.html',
				controller: 'myTasksCtrl'
		})
	//		--------------------	Operators PAGES --------------------
		.when('/operators/calendar', {
				templateUrl: 'pages/operators/calendar.html',
				controller: 'calendarCtrl'
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

myApp.directive('fileModel', ['$parse', function ($parse) {
    return {
    restrict: 'A',
    link: function(scope, element, attrs) {
        var model = $parse(attrs.fileModel);
        var modelSetter = model.assign;

        element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
        });
    }
   };
}]);

myApp.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl, name){
         var fd = new FormData();
         fd.append('file', file);
         fd.append('name', name);
         $http.post(uploadUrl, fd, {
             transformRequest: angular.identity,
             headers: {'Content-Type': undefined,'Process-Data': false}
         })
         .then(function(response){
            console.log("Success");
            console.log(response.data);
         });
     }
 }]);

myApp.directive('fdInput', ['$timeout', function ($timeout) {
    return {
        link: function (scope, element, attrs) {
            element.on('change', function  (evt) {               
                alert(evt.target.files[0].name);               
                alert($('#$index').val());                
            });
        }
    }
}]);

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

myApp.directive('fullSystem', function(){
	return {
		require: '^^lineupForm',
		templateUrl: 'pages/lineups/partials/stations/Full System/fullSystem.html',
//		controller: 'newLineupCtrl',
//		scope: {
//			array: '=',
//			sec: '=',
//		},
		link: function(scope, element, attrs){
			
		}
	}
});

myApp.directive('talynA', function(){
	return {
		require: ['^^lineupForm'],
		templateUrl: 'pages/lineups/partials/stations/Full System/TalynA/TalynA.html',
//		controller: 'newLineupCtrl',
//		scope: {
//			array: '=',
//			sec: '=',
//		},
		link: function(scope, element, attrs){
			
		}
	}
});
myApp.directive('talynM', function(){
	return {
		require: ['^^lineupForm'],
		templateUrl: 'pages/lineups/partials/stations/Full System/TalynM/TalynM.html',
//		controller: 'newLineupCtrl',
//		scope: {
//		},
		link: function(scope, element, attrs){
			this.lineup = scope.lineup;
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

myApp.factory('AuthService', function(testParams, $http, Session, $cookies){
	var authService = {};
	var site = testParams.site
	
	authService.login = function (credentials){
		return $http.post(site+'/auth/login', {user: credentials})
		.then(function(res){
//			console.log(res.data);
			if(res.data.login = true){
				Session.create(res.data.__ci_last_regenerate, res.data.userId, res.data.username, res.data.firstName, res.data.lastName, res.data.rank, res.data.email, res.data.group_id);
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
	testParams.days = [
		{id: 1, name: 'Sunday'},
		{id: 2, name: 'Monday'},
		{id: 3, name: 'Tuesday'},
		{id: 4, name: 'Wednesday'},
		{id: 5, name: 'Thursday'},
		{id: 6, name: 'Friday'},
//		{id: 0, name: 'Saturday'},
	];
	testParams.site = "http://wigig-299";
	var site = testParams.site;
	testParams.params = {};
	testParams.lineups = {};
	
	$http.get(site+'/params/structs')
	.then(function(response){
		testParams.structs = response.data;
	})
	
	$http.get(site+'/params/allChips')
	.then(function(response){
		testParams.params.allChips = response.data;
	})
	$http.get(site+'/params/allParams')
	.then(function(response){
		testParams.params.allParams = response.data;
//		console.log(response.data);
	})
	
	$http.get(site+'/params/workStations')
	.then(function(response){
		testParams.params.workStations = response.data;
	});
	
	$http.get(site+'/params/testTypes')
	.then(function(response){
		testParams.params.testTypes = response.data;
	});
	
	$http.get(site+'/params/lineupParams')
	.then(function(response){
		testParams.lineups = response.data;
//		console.log(response.data);
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
	testParams.params.txGainRow = ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30'];
	testParams.params.dacFssel = ['0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40'];
	
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
		'None',
		'-60',
		'-50',
		'-40',
		'-30',
		'-20',
		'-10',
		'0',
		'10',
		'15',
		'20',
		'25',
		'30',
		'40',
		'50',
		'55',
		'60',
		'65',
		'70',
		'75',
		'80',
		'85',
		'90',
		'95',
		'100',
		'105',
	];
	
	testParams.params.priorityList = [
		{display_name: '1 - Highest', value: '1'},
		{display_name: '2', value: '2'},
		{display_name: '3', value: '3'},
		{display_name: '4', value: '4'},
		{display_name: '5', value: '5'},
		{display_name: '6', value: '6'},
		{display_name: '7 - Lowest', value: '7'},
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
	
	testParams.params.mcsList = ['12', '14'];
	testParams.params.antList = [
		'0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31'
	];
	testParams.params.numAntsList = [
		'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32'
	];
	testParams.params.sectorList = [
		'1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','32','33','34','35','36','37','38','39','40','41','42','43','44','45','46','47','48','49','50','51','52','53','54','55','56','57','58','59','60','61','62','63','64','65','66','67','68','69','70','71','72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101','102','103','104','105','106','107','108','109','110','111','112','113','114','115','116','117','118','119','120','121','122','123','124','125','126','127',
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
	this.create = function(sessionId, userId, username, fisrtName, lastName, rank, email, groupId){
		this.id = sessionId;
		this.userId = userId;
		this.username = username;
		this.fisrtName = fisrtName;
		this.lastName = lastName;
		this.rank = parseInt(rank);
		this.email = email;
		this.group_id = groupId;
	};
	
	this.destroy = function(){
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