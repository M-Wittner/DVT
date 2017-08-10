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
		.when('plans/:id/edit', {
			templateUrl: 'pages/plans/edit.html',
			controller: 'editPlanCtrl'
	})
		.otherwise({redirectTo: '/'});
	
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


myApp.service('Session', function(){
	this.create = function(sessionId, userId, userRole){
		this.id = sessionId;
		this.userId = userId;
		this.userRols = userRole;
	};
	
	this.destory = function(){
		this.id = null;
		this.userId = null;
		this.userRole = null;
	};
});

myApp.factory('AuthService', function($http, Session){
	var authService = {};
	
	authService.login = function (credentials){
		return $http.post('http://wigig-584:3000/login', {user: credentials})
		.then(function(res){
			console.log(res.data);
			Session.create(res.data.id, res.data.user.id, res.data.user.role);
			return res.data.user;
		});
	};
	
	authService.isAuthenticated = function(){
			return !!Session.userId;
		};
		
		authService.isAuthorized = function(authorizedRoles){
			if(!angular.isArray(authorizedRoles)){
				authorizedRoles = [authorizedRoles];
			}
			return(authService.isAuthenticated() && authorizedRoles.indexOf(Session.userRole) !== -1);
		};
		
		return authService;
});