var myApp = angular.module('myApp', ['ngRoute', 'ui.bootstrap', 'btorfs.multiselect', 'ngFlash', 'ngCookies']);

myApp.config(['$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {
	
	$routeProvider
		.when('/', {
		templateUrl: 'pages/home.html',
		controller: 'homeCtrl'
	})
		.when('/register', {
		templateUrl: 'pages/register.html',
		controller: 'regCtrl'
	})
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
		.when('/admin', {
			templateUrl: 'pages/admin/panel.html',
			controller: 'adminCtrl'
	})
		.when('/admin/newchip', {
			templateUrl: 'pages/admin/newchip.html',
			controller: 'chipCtrl'
	})
		.when('/admin/newtest', {
			templateUrl: 'pages/admin/newtest.html',
			controller: 'testCtrl'
	})
		.when('/admin/chiplist', {
			templateUrl: 'pages/admin/chiplist.html',
			controller: 'chiplistCtrl'
	})
		.otherwise({redirectTo: '/'});
	;
	
	$locationProvider.hashPrefix('');

}]);

myApp.directive('testForm', function(){
	return {
		templateUrl: 'pages/plans/newTest.html',
		controller: 'testCtrl',
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
				Session.create(res.data.__ci_last_regenerate, res.data.userId, res.data.username);
				return res.data;	
			} else {
				console.log('Error! Not logged in');
			}
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
	testParams.params = {};
	
	testParams.params.stationList = [
		"R-CB1",
		'R-CB2',
		'M-CB1',
		'M-CB2',
		'FS',
		'RFC/CAL',
		'PTAT/ABS/Vgb+TEMP',
	];
	
	testParams.params.newTest = [
		'R - Stations',
		'M - Stations',
		'Full System',
		'RFC/CAL',
		'PTAT/ABS/Vgb+TEMP',
	];
	
	testParams.params.nameListM = {};
	$http.get('http://wigig-584/params/testsM')
	.then(function(response){
		testParams.params.nameListM = response.data;
	});
	
	testParams.params.nameListR = {};
	$http.get('http://wigig-584/params/testsR')
	.then(function(response){
		testParams.params.nameListR = response.data;
//			console.log(response.data);
	});
	
	testParams.params.xifList = [
		'0',
		'1',
		'2',
		'3',
		'4',
		'5',
		'6',
		'7',
	];
	testParams.params.chipListM = {};
    $http.get('http://wigig-584/params/chipsM')
	.then(function(response){
		testParams.params.chipListM = response.data;
	});
	
	testParams.params.chipListR = {};
	$http.get('http://wigig-584/params/chipsR')
	.then(function(response){
		testParams.params.chipListR = response.data;
//		console.log(response.data);
	});
	
	testParams.params.tempList = [
		'-40 C',
		'-30 C',
		'-10 C',
		'0 C',
		'25 C',
		'40 C',
		'60 C',
		'80 C',
		'85 C',
		'105 C',
	];
	
	testParams.params.priorityList = [
		'1',
		'2',
		'3'
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
//	testParams.params.chList2 = [
//		'6',
//		'7',
//		'8',
//		'9',
//		'10'
//	];
	
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
	this.create = function(sessionId, userId, username){
		this.id = sessionId;
		this.userId = userId;
		this.username = username;
//		this.userRole = userRole;
	};
	
	this.destroy = function(){
		this.id = null;
		this.userId = null;
		this.userRole = null;
	};
});