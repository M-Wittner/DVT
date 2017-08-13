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
		.when('/plans/:id/edit', {
			templateUrl: 'pages/plans/edit.html',
			controller: 'editPlanCtrl'
	})
		.when('/plans/:planId/test/:testId/comments/new', {
			templateUrl: 'pages/comments/new.html',
			controller: 'newCommentCtrl'
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


myApp.factory('AuthService', function($http, Session, $cookies){
	var authService = {};
	
	authService.login = function (credentials){
		return $http.post('http://wigig-584:3000/auth/login', {user: credentials})
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