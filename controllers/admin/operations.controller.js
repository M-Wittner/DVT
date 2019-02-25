myApp.controller('logCtrl', ['$scope', '$rootScope', '$interpolate', '_', '$filter','$http', 'Flash', 'AuthService', 'NgTableParams', 'testParams', '$stateParams', 'uibDateParser', function ($scope, $rootScope, $interpolate, _,$filter, $http, Flash, AuthService, NgTableParams, testParams, $stateParams) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	$scope.isAuthenticated = true;
	var site = $rootScope.site;
	var scope = $scope;
	$scope.testParams = testParams;
	$scope.parse = testParams.parseDate;
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
	if($scope.isAuthenticated == true) {
		$http.get(site+'/admin/operations')
		.then(function(response) {
			$scope.data = response.data;
			console.log($scope.data);
			$scope.loaded = false;
			$scope.operatorNames = testParams.params.operatorList.map(operator => {
				return {id: operator.name, title: operator.name};
			})
			$scope.stations = testParams.params.workStations.map(station => {
				return {id: station.name, title: station.name};
			});
			$scope.colsList = [
				{field: 'operation_id', title: 'Operation ID', filter: {operation_id: 'number'}, show: true},
				{field: 'work_station', title: 'Station', filter: {work_station: 'select'}, filterData: $scope.stations, show: true},
				{field: 'date', title: 'Date', filter: {date: 'text'}, show: true},
				{field: 'test_name', title: 'Test Name', filter: {test_name: 'text'}, show: true},
				{field: 'plan_id', title: 'Plan ID',filter: {plan_id: 'number'} ,show: true},
				{field: 'test_id', title: 'Test ID', filter: {test_id: 'number'}, show: true},
				{field: 'chipR', title: 'ChipR', filter: {chip_r_sn: 'text'}, show: true},
				{field: 'chipM', title: 'ChipM', filter: {chip_m_sn: 'text'}, show: true},
				{field: 'status', title: 'Status', filter: {status: 'text'}, show: true},
				{field: 'user', title: 'Operator', filter: {user: 'select'}, filterData: $scope.operatorNames, show: true},
				{field: 'View', title: 'View', show: true},
			];
			$scope.cols = _.indexBy($scope.colsList, "field");
			console.log($scope.cols)
		})
		.then(function(){
			$scope.tableParams = new NgTableParams({},{
				total: $scope.data.length,
				dataset: $scope.data,
				filterLayout: 'horizontal',
			});
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
	
	$scope.updateStatus = function(operation){
//		chip.flag = flag == '' ? null : flag;
		$http.post(site+'/plans/operationstatus', operation)
		.then(function(response){
//			console.log(response.data);
			var status = response.data
			operation.status_id = status.test_status;
			operation.status = status.status;
			var message = '<strong>Operation #' + operation.operation_id+'</strong> Status has been updated <strong>('+status.status+')</strong>';
			var id = Flash.create('success', message, 6000);
		});
	};
	
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