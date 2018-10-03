myApp.controller('searchCtrl', ['$scope', '$location','$http', 'Flash', '$cookies', '$window','testParams', 'AuthService', 'NgTableParams', 'fileSaver', 'Blob', function ($scope, $location, $http, Flash, $cookies, $window, testParams, AuthService, NgTableParams, fileSaver, Blob) {
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
	
	$scope.uploadFile = function(){
		var file = $scope.myFile
		var uploadUrl = site+"/admin/upload";
		var text = $scope.myFile.name;
		console.log(text);
		fileUpload.uploadFileToUrl(file, uploadUrl, text);
   };
	
}]);