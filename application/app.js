var domain = "";
var app = angular.module('app', ['ngCookies', 'blockUI', 'cgNotify', 'pascalprecht.translate', 'tmh.dynamicLocale', 'angular-loading-bar', 'ngAnimate', 'ngTable', 'ui.router', 'directives.customvalidation.customValidationTypes', 'ngSanitize', 'ui.select', 'ui.bootstrap', 'ui.bootstrap.showErrors', 'google.places', 'sx.wizard', 'ngBootstrap', 'ngFileSaver', 'ui.bootstrap.datetimepicker', 'ui.dateTimeInput']).config(config).run(run);

/* Event Section */
var observerCallbacks = [];
var RaiseEvent = function (event, value) {
    angular.forEach(observerCallbacks, function (eventHandlers) {
        if (eventHandlers.key == event)
            eventHandlers.callback(value);
    });
};

var OnLoggedInStatusChanged = function (value) {
    RaiseEvent('LoggedInEvent', value);
};

var OnIdentifyChanged = function (value) {
    RaiseEvent('IdentifyChangedEvent', value);
}
/*---------------*/

Date.prototype.getString = function () {
    var yyyy = this.getFullYear();
    var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1);
    var dd = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
    return "".concat(dd).concat('/' + mm).concat('/' + yyyy);
};

var holidays = ['13/09/2015', '14/09/2015', '15/09/2015', '22/09/2015', '23/09/2015', '27/09/2015', '28/09/2015', '29/09/2015', '30/09/2015', '01/10/2015', '02/10/2015', '03/10/2015', '04/10/2015', '05/10/2015', '06/10/2015', '06/12/2015', '07/12/2015', '08/12/2015', '09/12/2015', '10/12/2015', '11/12/2015', '12/12/2015', '13/12/2015', '14/12/2015', '25/01/2016', '23/02/2016', '23/03/2016', '24/03/2016', '25/03/2016', '22/04/2016', '23/04/2016', '24/04/2016', '25/04/2016', '26/04/2016', '27/04/2016', '28/04/2016', '29/04/2016', '30/04/2016', '22/05/2016', '26/05/2016', '11/06/2016', '12/06/2016', '13/06/2016', '13/08/2016', '14/08/2016', '19/08/2016', '24/09/2016', '02/10/2016', '03/10/2016', '04/10/2016', '11/10/2016', '12/10/2016', '16/10/2016', '17/10/2016', '18/10/2016', '19/10/2016', '20/10/2016', '21/10/2016', '22/10/2016', '23/10/2016', '24/10/2016', '25/10/2016', '24/12/2016', '25/12/2016', '26/12/2016', '27/12/2016', '28/12/2016', '29/12/2016', '30/12/2016', '31/12/2016', '01/01/2017', '11/02/2017', '11/03/2017', '12/03/2017', '13/03/2017', '10/04/2017', '11/04/2017', '12/04/2017', '13/04/2017', '14/04/2017', '15/04/2017', '16/04/2017', '17/04/2017', '18/04/2017', '10/05/2017', '14/05/2017', '30/05/2017', '31/05/2017', '01/06/2017', '31/07/2017', '01/08/2017', '07/08/2017', '16/09/2017', '20/09/2017', '21/09/2017', '22/09/2017', '29/09/2017', '30/09/2017', '04/10/2017', '05/10/2017', '06/10/2017', '07/10/2017', '08/10/2017', '09/10/2017', '10/10/2017', '11/10/2017', '12/10/2017', '13/10/2017', '12/12/2017', '13/12/2017', '14/12/2017', '15/12/2017', '16/12/2017', '17/12/2017', '18/12/2017', '19/12/2017', '20/12/2017', '31/01/2018', '28/02/2018', '01/03/2018', '02/03/2018', '30/03/2018', '31/03/2018', '01/04/2018', '02/04/2018', '03/04/2018', '04/04/2018', '05/04/2018', '06/04/2018', '07/04/2018', '29/04/2018', '03/05/2018', '19/05/2018', '20/05/2018', '21/05/2018', '21/07/2018', '22/07/2018', '27/07/2018', '01/09/2018', '09/09/2018', '10/09/2018', '11/09/2018', '18/09/2018', '19/09/2018', '23/09/2018', '24/09/2018', '25/09/2018', '26/09/2018', '27/09/2018', '28/09/2018', '29/09/2018', '30/09/2018', '01/10/2018', '02/10/2018', '02/12/2018', '03/12/2018', '04/12/2018', '05/12/2018', '06/12/2018', '07/12/2018', '08/12/2018', '09/12/2018', '10/12/2018', '21/01/2019', '19/02/2019', '20/03/2019', '21/03/2019', '22/03/2019', '19/04/2019', '20/04/2019', '21/04/2019', '22/04/2019', '23/04/2019', '24/04/2019', '25/04/2019', '26/04/2019', '27/04/2019', '19/05/2019', '23/05/2019', '08/06/2019', '09/06/2019', '10/06/2019', '10/08/2019', '11/08/2019', '16/08/2019', '21/09/2019', '29/09/2019', '30/09/2019', '01/10/2019', '08/10/2019', '09/10/2019', '13/10/2019', '14/10/2019', '15/10/2019', '16/10/2019', '17/10/2019', '18/10/2019', '19/10/2019', '20/10/2019', '21/10/2019', '22/10/2019', '22/12/2019', '23/12/2019', '24/12/2019', '25/12/2019', '26/12/2019', '27/12/2019', '28/12/2019', '29/12/2019', '30/12/2019'];

var calendarDisabled = function (date, mode) {
    var stringDate = (date.getString());
    var isHoliday = _.find(holidays, function (holiday) {
        return holiday === stringDate;
    });

    return (isHoliday != null || (mode === 'day' && (date.getDay() === 5 || date.getDay() === 6)));
}

app.factory('FlashService', function ($rootScope) {

    var service = {};
    var lastAction = true;

    service.Success = Success;
    service.Error = Error;
    service.Clear = Clear;
    service.GetLastActionStatus = GetLastActionStatus;

    initService();

    return service;

    function initService() {
        $rootScope.$on('$locationChangeStart', function () {
            clearFlashMessage();
        });

        function clearFlashMessage() {
            var flash = $rootScope.flash;
            if (flash) {
                if (!flash.keepAfterLocationChange) {
                    delete $rootScope.flash;
                } else {
                    // only keep for a single location change
                    flash.keepAfterLocationChange = false;
                }
            }
        }
    }

    function Success(message, keepAfterLocationChange) {
        lastAction = true;
        $rootScope.flash = {
            message: message,
            type: 'success',
            keepAfterLocationChange: keepAfterLocationChange
        };
    }

    function GetLastActionStatus() {
        return lastAction;
    }

    function Clear() {
        delete $rootScope.flash;
    }

    function Error(message, keepAfterLocationChange) {
        lastAction = false;
        $rootScope.flash = {
            message: message,
            type: 'error',
            keepAfterLocationChange: keepAfterLocationChange
        };
    }

});

app.filter('iconify', function ($sce) {
    return function (input) {
        return $sce.trustAsHtml(input == 'true' ? '<a ng-click="download()" class="btn btn-xs btn-info glyphicon glyphicon-floppy-save"></a>' : '');
    }
});

app.filter('customDateFilter', function ($filter) {
    return function (conversations, start_date, end_date, fieldName, DateFormat) {
        var result = [];
        if (DateFormat == undefined)
            DateFormat = "DD/MM/YYYY";

        if (start_date == undefined || end_date == undefined)
            return conversations;
        if (start_date.toDate().getTime() == end_date.toDate().getTime()) {
            return conversations;
        }

        start_date = start_date.toDate();
        end_date = end_date.toDate();

        if (conversations && conversations.length > 0) {
            angular.forEach(conversations, function (conversation, index) {
                var conversationDate = moment(conversation[fieldName], DateFormat).toDate();
                if (conversationDate >= start_date && conversationDate <= end_date) {
                    result.push(conversation);
                }
            });

            return result;
        }
    };
});

app.service('languageService', function ($q, $http, $rootScope, $cookieStore, $translate, tmhDynamicLocale, $window) {
    var lang = [];
    var currentLocale = $translate.proposedLanguage();
    var currentLanguage_id;
    this.getLanguages = function () {
        var deferred = $q.defer();
        if (lang.length == 0) {
            lang.push({
                language_id: 10,
                language: 'he',
                locale: 'he_HE',
                language_name: 'עברית'
            });
            lang.push({
                language_id: 1,
                language: 'en',
                locale: 'en_EN',
                language_name: 'English'
            });
        }
        deferred.resolve(lang);
        return deferred.promise;
    }

    this.getCurrentLanguage = function () {
        return _.find(lang, function (obj) {
            return obj.locale == currentLocale
        });
    }

    this.setLanguage = function (language) {
        currentLocale = language;
        $translate.use(language.locale);
        $window.location.reload();
    };

    this.getLanguageId = function () {
        return currentLanguage_id;
    }

    $rootScope.$on('$translateChangeSuccess', function (event, data) {
        currentLanguage_id = _.find(lang, function (obj) {
            return obj.locale == data.language
        }).language_id;
        $http.defaults.headers.common['Accept-Language'] = currentLanguage_id;
        document.documentElement.setAttribute('lang', data.language);
    });
});

app.service('userService', function ($q, $http, $location, $cookieStore, AuthenticationService) {


    var currentUser = null;

    this.OnLoggedInStatusChangedRegister = function (callback) {
        observerCallbacks.push({
            key: 'LoggedInEvent',
            callback: callback
        });
    };

    this.OnIdentifyChangedRegister = function (callback) {
        observerCallbacks.push({
            key: 'IdentifyChangedEvent',
            callback: callback
        });
    };

    this.hasChangeIdentityPermission = function () {
        return _.find(currentUser.security_group.permissions, function (permission) {
            return permission.permission.name == 'CHANGE_IDENTITY'
        }) != null;
    }

    this.hasPermission = function (permissionName, canWrite) {
        return _.find(currentUser.security_group.permissions, function (permission) {
            return permission.permission.name == permissionName && (canWrite == false || permission.write_permission == 1)
        }) != null;
    }

    this.getAllUsers = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/user/all').success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.getUsersActivity = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/user/users_activity').success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.blockUser = function (user_id) {
        var deferred = $q.defer();
        $http.post(domain + '/api/user/block_user', {
            user_id: user_id
        }).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.update = function (data) {
        var deferred = $q.defer();
        $http.put(domain + '/api/user/update', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.identifyAs = function (data) {
        var deferred = $q.defer();
        $http.put(domain + '/api/user/change_identity', data).success(function (response) {
            $http.get(domain + '/api/user/self').success(function (data) {
                currentUser = data;
                OnLoggedInStatusChanged(data);
            });
            //OnIdentifyChanged(data.depositor.company_name);
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.changeLogisticSite = function (data) {
        var deferred = $q.defer();
        $http.put(domain + '/api/user/change_logistic_site', data).success(function (response) {
            $http.get(domain + '/api/user/self').success(function (data) {
                currentUser = data;
                OnLoggedInStatusChanged(data);
            });
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.delete = function (user_id) {
        var deferred = $q.defer();
        $http.delete(domain + '/api/user/delete/' + user_id).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.create = function (data) {
        var deferred = $q.defer();
        $http.post(domain + '/api/user/create', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.change_password = function (data) {
        var deferred = $q.defer();
        $http.put(domain + '/api/user/change_password', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.getCurrentUser = function () {
        var deferred = $q.defer();
        var User = $cookieStore.get('globals') || {};
        if (User.currentUser) {
            if (currentUser != null) {
                OnLoggedInStatusChanged(currentUser);
                deferred.resolve(currentUser);
            } else {
                $http.get(domain + '/api/user/self').success(function (data) {
                    currentUser = data;
                    OnLoggedInStatusChanged(data);
                    deferred.resolve(data);
                }).error(function (data) {
                    currentUser = null;
                    OnLoggedInStatusChanged(null);
                    $location.path('/login');
                    deferred.reject(null);
                });
            }
        } else {
            currentUser = null;
            $location.path('/login');
            deferred.reject(null);
        }
        return deferred.promise;
    }

    this.logout = function () {
        currentUser = null;
        OnLoggedInStatusChanged(null);
        AuthenticationService.ClearCredentials();
    }

});

app.service('depositorService', function ($q, $http) {

    this.depositories = null;

    this.getDepositories = function () {
        var deferred = $q.defer();
        if (this.depositories == null) {
            $http.get(domain + '/api/depositories/all').then(function (success) {
                this.depositories = success.data;
                deferred.resolve(success.data);
            });
        } else {
            deferred.resolve(this.depositories);
        }
        return deferred.promise;
    }
});

app.service('customerService', function ($q, $http) {
    this.getCustomers = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/customer/all').then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('loginHistoryService', function ($q, $http) {
    this.getLoginHistories = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/login_history/all').then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('managementService', function ($q, $http) {
    this.unblockIp = function (ipAddress) {
        var deferred = $q.defer();
        $http.post(domain + '/api/management/unblock', ipAddress).then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('supplierService', function ($q, $http) {
    this.getSuppliers = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/suppliers/all').then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('filterService', function ($q, $http) {
    this.getFilters = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/filters/all').then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('itemService', function ($q, $http) {
    var items = null;
    this.getItems = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/items/get').then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getAdditionalInfo = function (product_id) {
        var deferred = $q.defer();
        $http.get(domain + '/api/items/additional_info/' + product_id).then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getInventoryMovements = function (product_id) {
        var deferred = $q.defer();
        $http.get(domain + '/api/items/inventory_movements/' + product_id).then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('inventoryService', function ($q, $http) {
    this.getInventory = function (mode) {
        var deferred = $q.defer();
        if (mode == 0 || mode == undefined) {
            $http.get(domain + '/api/inventory/get').then(function (success) {
                deferred.resolve(success.data);
            });
        } else {
            $http.get(domain + '/api/inventory/collectively').then(function (success) {
                deferred.resolve(success.data);
            });
        }
        return deferred.promise;
    }
});

app.service('stockMovementsService', function ($q, $http) {
    this.getStockMovements = function (filters) {
        return $http.post(domain + '/api/stock_movements/get', {filters: filters});
    }
    this.getItemsByCode = function (code) {
        return $http.post(domain + '/api/stock_movements/get_by_code', {code: code});
    }
});

app.service('logisticSitesService', function ($q, $http) {
    this.getSites = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/logistic_sites/get').then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('groupService', function ($q, $http) {

    this.groups = null;

    this.getSecutiryGroups = function () {
        var deferred = $q.defer();
        if (this.groups == null) {
            $http.get(domain + '/api/security_groups/all').then(function (success) {
                this.groups = success.data;
                deferred.resolve(success.data);
            });
        } else {
            deferred.resolve(this.groups);
        }
        return deferred.promise;
    }

    this.create = function (data) {
        var deferred = $q.defer();
        $http.post(domain + '/api/security_groups/create', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.update = function (data) {
        var deferred = $q.defer();
        $http.put(domain + '/api/security_groups/update', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.delete = function (user_id) {
        var deferred = $q.defer();
        $http.delete(domain + '/api/security_groups/delete/' + user_id).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

});

app.service('orderService', function ($q, $http) {

    this.orders = null;

    this.getOrders = function () {
        var deferred = $q.defer();
        if (this.orders == null) {
            $http.get(domain + '/api/orders/get').then(function (success) {
                this.orders = success.data;
                deferred.resolve(success.data);
            });
        } else {
            deferred.resolve(this.orders);
        }
        return deferred.promise;
    }

    this.getOrdersChunk = function (chunk_number) {
        var deferred = $q.defer();
        if (this.orders == null) {
            $http.get(domain + '/api/orders/chunk/' + chunk_number).then(function (success) {
                //this.orders = success.data;
                deferred.resolve(success.data);
            });
        } else {
            deferred.resolve(this.orders);
        }
        return deferred.promise;
    }

    this.getOrderSaleUnit = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/orders/sale_unit').then(function (success) {
            this.orders = success.data;
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getOrderItems = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/orders/items').then(function (success) {
            this.orders = success.data;
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getOrderStock = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/orders/stock/0').then(function (success) {
            this.orders = success.data;
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getOrderSurfaceStock = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/orders/stock/1').then(function (success) {
            this.orders = success.data;
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getPod = function (orderCode) {
        var deferred = $q.defer();
        $http.get(domain + '/api/orders/pod/' + orderCode, {
            responseType: "arraybuffer"
        }).then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.create = function (data) {
        var deferred = $q.defer();
        $http.post(domain + '/api/orders/create', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.create_customer = function (data) {
        var deferred = $q.defer();
        $http.post(domain + '/api/orders/create_customer', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.getDetails = function (order_id) {
        var deferred = $q.defer();
        $http.get(domain + '/api/orders/details/' + order_id).then(function (success) {
            this.orders = success.data;
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getAdditionalDetails = function (order_id) {
        var deferred = $q.defer();
        $http.get(domain + '/api/orders/additional_details/' + order_id).then(function (success) {
            this.orders = success.data;
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});


app.service('receiptService', function ($q, $http) {

    this.receipts = null;

    this.getReceiptPdfReport = function (receipts) {
        var deferred = $q.defer();
        $http.post(domain + '/api/reports/receipt', {
            receipts: receipts.join(", ")
        }, {
            responseType: "arraybuffer"
        }).then(function (success) {
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }

    this.getReceipts = function () {
        var deferred = $q.defer();
        if (this.receipts == null) {
            $http.get(domain + '/api/receipts/get').then(function (success) {
                this.receipts = success.data;
                deferred.resolve(success.data);
            });
        } else {
            deferred.resolve(this.receipts);
        }
        return deferred.promise;
    }

    this.getReceiptExcel = function (receiptId) {
        var deferred = $q.defer();

        $http.get(domain + '/api/receipts/receipt_excel/' + receiptId).then(function (success) {
            deferred.resolve(success.data);
        });

        return deferred.promise;
    }

    this.getItems = function () {
        var deferred = $q.defer();
        $http.get(domain + '/api/receipts/items').success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }


    this.create = function (data) {
        var deferred = $q.defer();
        $http.post(domain + '/api/receipts/create', data).success(function (response) {
            deferred.resolve(response);
        });
        return deferred.promise;
    }

    this.getAdditionalDetails = function (receipt_id) {
        var deferred = $q.defer();
        $http.get(domain + '/api/receipts/additional_details/' + receipt_id).then(function (success) {
            //this.orders = success.data;
            deferred.resolve(success.data);
        });
        return deferred.promise;
    }
});

app.service('permissionService', function ($q, $http) {

    this.permissions = null;

    this.getPermissions = function () {
        var deferred = $q.defer();
        if (this.permissions == null) {
            $http.get(domain + '/api/permission/all').then(function (success) {
                this.permissions = success.data;
                deferred.resolve(success.data);
            });
        } else {
            deferred.resolve(this.permissions);
        }
        return deferred.promise;
    }
});

app.directive('ngTranslateLanguageSelect', function (languageService) {
    'use strict';

    return {
        restrict: 'A',
        replace: true,
        template: '' +
            '<div class="language-select" style="width:110px;">' +
            '<select class="form-control" ng-model="currentLanguage"' +
            'ng-options="language.language_name for language in languages"' +
            'ng-change="changeLanguage(currentLanguage)">' +
            '</select>' +
            '</div>' +
            '',
        controller: function ($scope, $window) {
            languageService.getLanguages().then(function (result) {
                $scope.languages = result;
                $scope.currentLanguage = languageService.getCurrentLanguage();
            });

            $scope.changeLanguage = function (locale) {
                languageService.setLanguage(locale);
            };
        }
    };
});

config.$inject = ['$stateProvider', '$urlRouterProvider', '$locationProvider', '$httpProvider', '$translateProvider', 'blockUIConfig'];

function config($stateProvider, $urlRouterProvider, $locationProvider, $httpProvider, $translateProvider, blockUIConfig) {
    $translateProvider.useSanitizeValueStrategy(null);

    //$locationProvider.html5Mode(true);

    blockUIConfig.message = "טוען נתונים...";

    blockUIConfig.requestFilter = function (config) {

        if (config.url.match(/\/api\/orders\/chunk\/.*/)) {
            return false;
        }
        if (config.url.match(/\/api\/stock_movements\/get_by_code/)) {
            return false;
        }

        var message;

        switch (config.method) {
            case 'GET':
                message = 'טוען נתונים...';
                break;

            case 'POST':
                message = 'שולח נתונים ...';
                break;

            case 'PUT':
                message = 'שולח נתונים ...';
                break;
        };

        return message;
    };

    $translateProvider.useStaticFilesLoader({
        prefix: 'languages/locale-',
        suffix: '.json'
    });

    $translateProvider.useLocalStorage(); // saves selected language to localStorage
    $translateProvider.preferredLanguage('he_HE'); // is applied on first load

    var interceptor = [
        '$q',
        '$rootScope',
        '$location',
        'FlashService',
        '$translate',
        function ($q, $rootScope, $location, FlashSevice, $translate) {

            var service = {
                'response': function (success) {
                    if ((success.status == 200 || success.status == 201) &&
                        success.data.status) {
                        FlashSevice.Success($translate.instant(success.data.messages));
                    } else {
                        //if(!FlashSevice.GetLastActionStatus())
                        //    FlashSevice.Clear();
                    }
                    return success;
                },
                'responseError': function (rejection) {
                    if (rejection.status == 403 ||
                        rejection.status == 404) {
                        if (rejection.data.messages != null)
                            FlashSevice.Error($translate.instant(rejection.data.messages));
                        else
                            $location.path('/');
                    }
                    if (rejection.status == 401 && $location.path() != "/login") {
                        OnLoggedInStatusChanged(null);
                        $location.path('/login');
                    }
                    if (rejection.status == 409)
                        FlashSevice.Error($translate.instant(rejection.data.messages));
                    return $q.reject(rejection);
                    //return rejection;
                }
            };

            return service;

        }
    ];

    $httpProvider.interceptors.push(interceptor);
    var originalWhen = $stateProvider.state;

    $stateProvider.state = function (path, route) {
        if (path != "login") {
            route.resolve || (route.resolve = {});
            angular.extend(route.resolve, {
                CurrentUser: function (userService) {
                    return userService.getCurrentUser();
                }
            });
        }

        return originalWhen.call($stateProvider, path, route);
    };

    $urlRouterProvider.otherwise('/login');
    $urlRouterProvider.when('/user_management', '/user_management/create_user');
    $urlRouterProvider.when('/permission_management', '/permission_management/add_permission_group');

    $stateProvider
        .state('home', {
            url: '/',
            templateUrl: 'home/home.view.html',
            controller: 'HomeController',
            controllerAs: 'vm'
        })
        .state('login', {
            url: '/login',
            templateUrl: 'login/login.view.html',
            controller: 'LoginController',
            controllerAs: 'vm',
            resolve: {
                languages: function (languageService) {
                    return languageService.getLanguages()
                }
            }
        })
        .state('user_management', {
            url: '/user_management',
            templateUrl: 'user_management/user_management.view.html',
            controller: 'UserManagementController',
            controllerAs: 'vm'
        })
        .state('user_management.create_user', {
            url: '/create_user',
            templateUrl: 'user_management/create_user.view.html',
            controller: 'UserManagementController',
            controllerAs: 'vm'
        })
        .state('user_management.edit_user', {
            url: '/edit_user',
            templateUrl: 'user_management/edit_user.view.html',
            controller: 'UserManagementController',
            controllerAs: 'vm'
        })
        .state('user_management.users_activity', {
            url: '/users_activity',
            templateUrl: 'user_management/users_activity.view.html',
            controller: 'UserManagementController',
            controllerAs: 'vm'
        })
        .state('permission_management', {
            url: '/permission_management',
            templateUrl: 'permission_management/permission_management.view.html',
            controller: 'PermissionManagementController',
            controllerAs: 'vm'
        })
        .state('permission_management.add_permission_group', {
            url: '/add_permission_group',
            templateUrl: 'permission_management/add_permission_group.view.html',
            controller: 'PermissionManagementController',
            controllerAs: 'vm'
        })
        .state('permission_management.edit_permission_group', {
            url: '/edit_permission_group',
            templateUrl: 'permission_management/edit_permission_group.view.html',
            controller: 'PermissionManagementController',
            controllerAs: 'vm'
        })
        .state('inventory', {
            url: '/inventory',
            templateUrl: 'inventory/inventory.view.html',
            controller: 'InventoryController',
            controllerAs: 'vm',
            resolve: {
                'inventoryData': function (inventoryService) {
                    return inventoryService.getInventory()
                },
                'filters': function (filterService) {
                    return filterService.getFilters()
                }
            }
        })
        .state('stock_movements', {
            url: '/stock_movements',
            templateUrl: 'stock_movements/stock_movements.view.html',
            controller: 'StockMovementsController',
            controllerAs: 'vm'
            /*,
                        resolve: {
                            'inventoryData': function (inventoryService) {
                                return inventoryService.getInventory()
                            },
                            'filters': function (filterService) {
                                return filterService.getFilters()
                            }
                        }*/
        })
        .state('items', {
            url: '/items',
            templateUrl: 'items/items.view.html',
            controller: 'ItemsController',
            controllerAs: 'vm',
            resolve: {
                'itemsData': function (itemService) {
                    return itemService.getItems()
                }
            }
        })
        .state('orders', {
            url: '/orders',
            templateUrl: 'orders/orders.view.html',
            controller: 'OrdersController',
            controllerAs: 'vm',
            resolve: {
                'ordersData': function (orderService) {
                    return orderService.getOrders()
                },
                'orderChunk': function (orderService) {
                    return orderService.getOrdersChunk(1)
                }
            }
        })
        .state('receipts', {
            url: '/receipts',
            templateUrl: 'receipts/receipts.view.html',
            controller: 'ReceiptsController',
            controllerAs: 'vm',
            resolve: {
                'receiptData': function (receiptService) {
                    return receiptService.getReceipts()
                }
            }
        })
        .state('profile', {
            url: '/profile',
            templateUrl: 'profile/profile.view.html',
            controller: 'ProfileController',
            controllerAs: 'vm'
        })
        .state('management', {
            url: '/management',
            templateUrl: 'management/management.view.html',
            controller: 'ManagementController',
            controllerAs: 'vm'
        })
        .state('login_history', {
            url: '/login_history',
            templateUrl: 'login_history/login_history.view.html',
            controller: 'LoginHistoryController',
            controllerAs: 'vm',
            resolve: {
                'historiesData': function (loginHistoryService) {
                    return loginHistoryService.getLoginHistories()
                }
            }
        });
}

run.$inject = ['$rootScope', '$location', '$cookieStore', '$http', '$timeout', 'userService', 'blockUI', 'languageService'];

function run($rootScope, $location, $cookieStore, $http, $timeout, userService, blockUI, languageService) {
    $rootScope.globals = $cookieStore.get('globals') || {};
    $http.defaults.headers.common['Cache-Control'] = 'no-cache';
    $http.defaults.headers.common['Pragma'] = 'no-cache';
    if ($rootScope.globals.currentUser) {
        if ($rootScope.globals.currentUser.token == undefined)
            $location.path('/login');
        $http.defaults.headers.common['Authorization'] = 'Basic ' + $rootScope.globals.currentUser.token;
        languageService.getLanguages().then(function (result) {
            $http.defaults.headers.common['Accept-Language'] = languageService.getCurrentLanguage().language_id;
        });
    } else
        $location.path('/login');

    $timeout(function () {
        userService.logout();
        $location.path('/login');
    }, (1000 * 60 * 30));

    $rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
        if ($rootScope.globals.currentUser) {
            $cookieStore.put("globals", $rootScope.globals);
        }
    });

}

app.controller('HeaderController', function ($scope, userService, logisticSitesService, $location, $translate) {

    $scope.location = $location.path();
    $scope.loggedIn = userService.currentUser != null;
    $scope.user = null;
    $scope.showManage = false;
    $scope.dropdown = [];
    $scope.showChangeSites = false;

    $scope.OnLoggedInStatusChanged = function (value) {
        if (value == null)
            $scope.loggedIn = false;
        else
            $scope.loggedIn = true;
        $scope.user = value;
        if ($scope.user != null) {
            $scope.dropdown = [];
            $scope.user.security_group.permissions.forEach(function (permission) {
                if (permission.permission.manage == 1) {
                    $scope.dropdown.push({
                        'text': $translate.instant(permission.permission.name),
                        'href': '#' + permission.permission.page
                    });
                }
            });
            if ($scope.dropdown.length > 0) {
                $scope.showManage = true;
            } else {
                $scope.showManage = false;
            }

            logisticSitesService.getSites().then(function (result) {
                if (result.length > 1) {
                    $scope.sites = result;
                    $scope.currentLogisticSite = _.find($scope.sites, function (obj) {
                        return obj.LogisticSiteId == $scope.user.logistic_site_id;
                    });
                    $scope.showChangeSites = true;
                } else {
                    $scope.showChangeSites = false;
                }
            });
        }
    }

    $scope.isActive = function (viewLocation) {
        return $location.path().indexOf(viewLocation) > -1;
    };

    $scope.changeLogisticSite = function (item) {
        userService.changeLogisticSite({
            logistic_site: item.LogisticSiteId
        }).then(function (result) {});
    };

    $scope.OnIdentifyChanged = function (value) {
        $scope.user.company.company_name = value;
    }

    userService.OnIdentifyChangedRegister($scope.OnIdentifyChanged);
    userService.OnLoggedInStatusChangedRegister($scope.OnLoggedInStatusChanged);

    $scope.permissionFilter = function (item) {
        return item.permission.permission_only == 0 && item.permission.manage == 0;
    }

    $scope.disconnect = function () {
        userService.logout();
        $location.path('/login');
    }
});