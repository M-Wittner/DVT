myApp.controller('searchCtrl', ['$scope', '$location','$http', 'Flash', '$cookies', '$window','testParams', 'AuthService', 'NgTableParams', 'fileUpload', function ($scope, $location, $http, Flash, $cookies, $window, testParams, AuthService, NgTableParams, fileUpload) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	if($scope.isAuthenticated == true) {
//		$ocLazyLoad.load('testModule.js');
		$http.get(site+'/admin/search')
		.then(function(response) {
//			console.log(AuthService.isAuthenticated());
			var data = response.data;
			$scope.TableParams = new NgTableParams({count:12}, {
				counts:[],
				total: data.length,
				dataset: data
			})
			console.log(response.data);
		});
		$scope.view = function(data){
			$location.path('/plans/'+data);
		};
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
		$location.path('/');
	};
//	console.log();
	
	$scope.filterChips = function(sweeps){
		var sweepsData = Object.values(sweeps);
		var chips = sweepsData.filter(sweep => sweep.data_type > 100);
		this.test.chips = chips[0].data;
	}
	
	$scope.uploadFile = function(fi){
		var file = $scope.myFile
//		console.log('file is ' );
//		console.dir(file);
//		console.dir(fi);
//		console.log($scope.myFile.name);

		var uploadUrl = site+"/admin/upload";
		var text = $scope.myFile.name;
		fileUpload.uploadFileToUrl(file, uploadUrl, text);
//		$http.post(uploadUrl, file)
//		.then(function(response){
//			console.log(response.data);
//		})
   };
	
}]);