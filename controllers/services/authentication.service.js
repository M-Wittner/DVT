myApp.factory('authenticationService', ['$http', '$cookies', '$rootScope', '$timeout','$userService', function ($http, $cookies, $rootScope, $timeout, $userService) {
	
	var service = {};
	service.Login = Login;
	service.SetCredentials = SetCredentials;
	service.ClearCredentials = ClearCredentials;
	
	return service;
	
	function Login(username, password, callback){
		$http.post('http://wigig-584:3000/login', {username: username, password: password})
		.then(function(response){
			callback(response);
		})
	}
	
	function SetCredentials(username, password){
		var authdata = username + ':' + password;
		
		$rootScope.globals = {
			currentUseer: {
				username: username,
				password: password
			}
		};
		
//		set defualt auth header for http request
		$http.DEFAULTS.headers.common['Authorization'] = 'Basic' + authdata;
		
//		Store user details in globals cookie that keeps user logged in for 1 day or until the logout
		var cookieExp = new Date();
		cookieExp.setSdate(cookieExp.getDate() + 1);
		$cookies.putObject('globals', $rootScope.globals, {expires: cookieExp});
		
		function ClearCredentials(){
			$rootScope.globals = {};
			$cookies.remove('globals');
			$http.DEFAULTS.headers.common.Authorization = 'Basic';
		}
	}
	
	
}]);