myApp.controller('logCtrl', ['$scope', '$rootScope', '$filter','$http', 'Flash', 'AuthService', 'NgTableParams', 'testParams', '$stateParams', 'uibDateParser', function ($scope, $rootScope, $filter, $http, Flash, AuthService, NgTableParams, testParams, $stateParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthenticated = true;
	var site = $rootScope.site;
	$scope.testParams = testParams;
	$scope.workStations = testParams.params.workStations;
	$scope.operators = testParams.params.operatorList;
	if($scope.isAuthenticated == true) {
		$http.get(site+'/admin/operations')
		.then(function(response) {
			$scope.parse = testParams.parseDate;
			$scope.idFilter = {
				start:{
					id: 'number',
					placeholder: 'Start'
				},
				end:{
					id: 'number',
					placeholder: 'End'
				}
			}
			$scope.dateFilter = {
				start:{
					id: 'text',
					placeholder: 'Start'
				},
				end:{
					id: 'text',
					placeholder: 'End'
				}
			}
			
//			var $data = response.data.splice(0, response.data.length - 30);
			$scope.tableParams = new NgTableParams({},{
//				 getData: function(params) {
//					 return $data;
//				},
				filterOptions: {filterFn: dateRange},
				dataset: response.data,
				filterLayout: 'horizontal',
			});
			
//			function idRange(data, filterValues){
//				return data.filter(function(item){
////					console.log(data);
////					console.log(filterValues);
//					var start = filterValues.start == null ? Number.MIN_VALUE : filterValues.start;
//					var end = filterValues.end == null ? Number.MAX_VALUE : filterValues.end;
//					return start <= item.operation_id && end >= item.operation_id;
//				})
//			}
			var minDate = new Date();
			minDate = minDate.getFullYear() - 2;
			minDate - Date.parse(minDate);
			function dateRange(data, filterValues){
				return data.filter(function(item){
//					console.log(filterValues);
					var tempDate = Date.parse(item.start_date);
					if(filterValues.end){
						var endDate =  new Date(filterValues.end);
						endDate.setDate(endDate.getDate()+1);				
						endDate = Date.parse(endDate);
					}
					if(filterValues.start){
						var startDate = new Date(filterValues.start);
						startDate = Date.parse(startDate);			
					}
//					console.log(endDate);
					var start = filterValues.start == null ? minDate : startDate;
					var end = filterValues.end == null ? minDate : endDate;
					return start <= tempDate && end >= tempDate;
				})
			}
			$scope.tableData = {};
			$scope.tableData.all =response.data;
//			$scope.tableData.current =response.data;
//			$scope.tableData.filtered =response.data;
			$scope.loaded = false;
		})
		.then(function(){
//			$scope.currentPage = 1;
//			$scope.itemsPerPage = 10;
//			$scope.setPage = function(page, data){
//				$scope.tableData.filtered = data;
//				if(page > 0){
//					var pageData = data.slice(
//					(page - 1) * $scope.itemsPerPage,
//					 page * $scope.itemsPerPage
//					);
//					$scope.tableData.current = pageData;
//				}
//			}
//			$scope.setPage(1, $scope.tableData.all);
		}).then(function(r){
			$scope.loaded = true;
		})
		$scope.filterTable = function(filter, col){
			console.log(filter);
			console.log(col);
			$scope.tableParams.filter({$s: filter});
//			var keys = Object.keys($scope.search);
//			keys.forEach((key)=> ($scope.search[key] == '') && delete $scope.search[key]);
//			if(!$.isEmptyObject($scope.search)){
//				keys = Object.keys($scope.search);
//				if(keys.length == 1){
//					filter = $scope.search[keys[0]];
//					col = keys[0];
//					$scope.tableData.filtered = $scope.tableData.all.filter(data =>{
//						return data[col] == filter;
//					});
//				}else{
//					$scope.tableData.filtered = $scope.tableData.filtered.filter(data =>{
//						return data[col] == filter;
//					});
//				}
//			}else{
//				$scope.tableData.filtered = $scope.tableData.all;
//			}
//			console.log($scope.search);
			console.log($scope.tableData.filtered);
//			$scope.setPage(1, $scope.tableData.filtered);
		}
	} else {
		var message = 'Please Login first!';
		var id = Flash.create('danger', message, 3500);
	};
	var vm = $scope;
	var today = testParams.today;
	$scope.parse = testParams.parseDate;
	$scope.date = {};
	$scope.today = new Date();
	$scope.dateTo = $scope.today;
	$scope.filter;
	
	
	$scope.filterDate = function(search){
		console.log(search);
		var filter;
	 if(typeof search.dateTo == 'undefined' && typeof search.dateFrom !='undefined'){
		 	filter = $scope.parse(search.dateFrom);
		}else if(typeof search.dateTo !='undefined' && typeof search.dateFrom !='undefined'){
			search.dateTo.setDate(search.dateTo.getDate()+1);
			var dateTo = Date.parse(search.dateTo);
			dateTo = dateTo;
			var dateFrom = Date.parse(search.dateFrom);
			var arr = []
//			console.log(Date.parse($scope.tableData[0].start_date));
			var data;
			if($scope.tableData.all.length == $scope.tableData.filtered.length){
				data = $scope.tableData.all;
			}else{
				data = $scope.tableData.filtered;
			}
			for(var i = 0; i < data.length; i++){
				var tempDate = Date.parse(data[i].start_date);
				if(search.dateTo >= tempDate && tempDate >= search.dateFrom){
					arr.push(data[i]);
				}
			}
			filter = arr;
			console.log(filter);
			$scope.setPage(1, filter);
		}else{
			$scope.setPage(1, $scope.tableData.all);
		}
	} 
  vm.fromOpened = false;
  vm.toOpened = false;

  vm.openDatePopup = function(value) {
		if(value == 4){
			vm.fromOpened = true;
		}else if(value == 5){
			vm.fromOpened = true;
    	vm.toOpened = true;	
		}
  };

  $scope.minDate = $scope.today.getFullYear() - 2;
  
  vm.formats = ['yyyy-dd-mm', 'dd-MMMM-yyyy', 'yyyy/MM/dd', 'dd.MM.yyyy', 'dd/MM/yyyy', 'shortDate'];
  vm.format = vm.formats[4];
	
}]);