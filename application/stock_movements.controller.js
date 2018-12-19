(function () {
    'use strict';

    angular
        .module('app')
        .controller('StockMovementsController', StockMovementsController);

    StockMovementsController.$inject = ['$scope', '$location', 'FlashService', '$timeout', '$http', 'notify', '$translate', '$filter', 'filterService', 'stockMovementsService', 'NgTableParams', '$q'];

    function StockMovementsController($scope, $location, FlashService, $timeout, $http, notify, $translate, $filter, filterService, stockMovementsService, NgTableParams, $q) {
        var vm = this;

        $scope.autoCompleteItem = {selected: undefined};

        $scope.formData = {
            SSCC: null,
            CC: null,
            ItemCode: null,
            BTC: null,
            CP: null
        };

        $scope.dateRangeEnd = new Date();
        $scope.dateRangeStart = new Date();

        $scope.items = [];

        $scope.refreshFunction = function (text) {
            if (text.length > 0) {
                stockMovementsService.getItemsByCode(text).then(success => {
                    $scope.items = success.data;           
                }, error => {
                    $scope.items = [];
                });
            }
            else
                $scope.items = [];
        };

        $scope.setItemCode = function(item){
            if(item !== undefined)
                $scope.formData.ItemCode = item.ItemCode;
            else
              $scope.formData.ItemCode = null;
        };

        var data = [];

        $scope.reset = function(){
            $scope.formData = {
                SSCC: null,
                CC: null,
                ItemCode: null,
                BTC: null,
                CP: null
            };
            $scope.dateRangeEnd = new Date();
            $scope.dateRangeStart = new Date();
            $scope.autoCompleteItem.selected = undefined;
            data = [];
            $scope.tableParams.data = [];
            $scope.tableParams.reload();
        }

        $scope.endDateBeforeRender = endDateBeforeRender
        $scope.endDateOnSetTime = endDateOnSetTime
        $scope.startDateBeforeRender = startDateBeforeRender
        $scope.startDateOnSetTime = startDateOnSetTime
        $scope.error = true;


        $scope.cols = [{
            title: $translate.instant('ROW_NUMBER'),
            titleAlt: 'ROW_NUMBER',
            sortable: false,
            show: true,
            field: 'ROW_NUMBER'
        }];

        $scope.checkAndSubmitForm = function () {
            $scope.formData.Date1 = $filter('date')($scope.dateRangeStart, 'dd/MM/yyyy');
            $scope.formData.Date2 = $filter('date')($scope.dateRangeEnd, 'dd/MM/yyyy');
            stockMovementsService.getStockMovements($scope.formData).then((success) => {
                data = success.data;
                FlashService.Clear();
                $scope.error = false;

                $scope.cols = [{
                    title: $translate.instant('ROW_NUMBER'),
                    titleAlt: 'ROW_NUMBER',
                    sortable: false,
                    show: true,
                    field: 'ROW_NUMBER'
                }];

                excelStyle = {
                    headers: true,
                    sheetid: 'LogisticInventory',
                    columns: []
                };

                for (var name in data[0]) {
                    var col = {
                        title: $translate.instant(name),
                        titleAlt: name,
                        sortable: name,
                        show: true,
                        field: name
                    };
                    col.filter = {};
                    col.filter[name] = 'text';
                    excelStyle.columns.push({
                        columnid: name,
                        title: $translate.instant(name)
                    });
                    $scope.cols.push(col);
                }
                $scope.tableParams.data = [];
                $scope.tableParams.reload();
            }, (error => {
                data = [];
                $scope.error = true;
                $scope.tableParams.data = [];
                $scope.tableParams.reload();
            }))
        }

        function startDateOnSetTime() {
            $scope.$broadcast('start-date-changed');
        }

        function endDateOnSetTime() {
            $scope.$broadcast('end-date-changed');
        }

        function startDateBeforeRender($dates) {
            if ($scope.dateRangeEnd) {
                var activeDate = moment($scope.dateRangeEnd);

                $dates.filter(function (date) {
                    return date.localDateValue() >= activeDate.valueOf()
                }).forEach(function (date) {
                    date.selectable = false;
                })
            }
        }

        function endDateBeforeRender($view, $dates) {
            if ($scope.dateRangeStart) {
                var activeDate = moment($scope.dateRangeStart).subtract(1, $view).add(1, 'minute');

                $dates.filter(function (date) {
                    return date.localDateValue() <= activeDate.valueOf()
                }).forEach(function (date) {
                    date.selectable = false;
                })
            }
        }

        var excelStyle = {
            headers: true,
            sheetid: 'LogisticStockMovements',
            columns: []
        };


        $scope.dateRangeFieldFormat = {
            EntryDate: "DD/MM/YYYY",
            ExD: "YYYY-MM-DD",
            MD: "YYYY-MM-DD"
        };

        $scope.filteredData = data;

        $scope.tableParams = new NgTableParams({
            page: 1,
            count: 20
        }, {
            filterDelay: 0,
            total: data.length,
            getData: function ($defer, params) {
                var filteredData = data;

                if (params.filter()) {
                    for (var filterParameter in params.filter()) {
                        if (params.filter()[filterParameter].startDate == null) {
                            var filterObject = {};
                            filterObject[filterParameter] = params.filter()[filterParameter];
                            filteredData = $filter('filter')(filteredData, filterObject, function (item1, item2) {
                                if (item2 == undefined || item2.length == 0)
                                    return true;
                                if (typeof (item1) === 'number') {
                                    return item1 == Number(item2);
                                }
                                if (item1 === null)
                                    return false;

                                return (item1.indexOf(item2) > -1);
                            });
                        } else
                            filteredData = $filter('customDateFilter')(filteredData, params.filter()[filterParameter].startDate, params.filter()[filterParameter].endDate, filterParameter, $scope.dateRangeFieldFormat[filterParameter]);
                    }
                }

                var orderedData = params.sorting() ?
                    $filter('orderBy')(filteredData, params.orderBy()) :
                    data;
                $scope.filteredData = orderedData;
                params.total(orderedData.length);
                $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
            },
            counts: []
        });

        $scope.exportData = function () {
            alasql('SELECT * INTO XLS("StockMovements.xls",?) FROM ?', [excelStyle, data]);
        };

        $scope.exportDataAfterFiltering = function () {
            alasql('SELECT * INTO XLS("StockMovements.xls",?) FROM ?', [excelStyle, $scope.filteredData]);
        };

        $scope.reload = function (mode) {
            inventoryService.getInventory(mode == undefined ? $scope.mode : mode).then(function (result) {
                data = result;
                $scope.cols = [{
                    title: $translate.instant('ROW_NUMBER'),
                    titleAlt: 'ROW_NUMBER',
                    sortable: false,
                    show: true,
                    field: 'ROW_NUMBER'
                }];

                excelStyle = {
                    headers: true,
                    sheetid: 'LogisticInventory',
                    columns: []
                };
                for (var name in data[0]) {
                    var col = {
                        title: $translate.instant(name),
                        titleAlt: name,
                        sortable: name,
                        show: true,
                        field: name
                    };
                    col.filter = {};
                    col.filter[name] = 'text';
                    excelStyle.columns.push({
                        columnid: name,
                        title: $translate.instant(name)
                    });
                    $scope.cols.push(col);
                }
                $scope.tableParams.data = [];
                $scope.tableParams.reload();
            });
        }
    }
})();