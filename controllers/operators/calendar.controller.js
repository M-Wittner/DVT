myApp.controller('calendarCtrl', ['$scope', '$compile', 'NgTableParams', '$location','$http', 'Flash', '$cookies', '$window', 'AuthService', 'testParams', 'uiCalendarConfig', function ($scope, $compile, NgTableParams, $location, $http, Flash, $cookies, $window, AuthService, testParams, uiCalendarConfig) {
	$scope.isAuthenticated = AuthService.isAuthenticated();
	var site = testParams.site;
	$scope.days = testParams.days;
//	console.log($scope.currentUser);
	
//	$scope.user = $scope.currentUser.username;
	
	if($scope.isAuthenticated == true) {
		
		var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
		
		var dayInWeek = date.getDay();
		var nextSunday = d + (7 - (dayInWeek % 7));
    
    /* event source that pulls from google.com */
    $scope.eventSource = {
            //url: "http://www.google.com/calendar/feeds/usa__en%40holiday.calendar.google.com/public/basic",
            //className: 'gcal-event',           // an option!
            //currentTimezone: 'America/Chicago' // an option!
    };
    /* event source that contains custom events on the scope */
    $scope.events = [
//      {title: 'All Day Event',start: new Date(y, m, 1)},
//      {title: 'Long Event',start: new Date(y, m, d - 5),end: new Date(y, m, d - 2)},
//      {id: 999,title: 'Repeating Event',start: new Date(y, m, d - 3, 16, 0),allDay: false},
//      {id: 999,title: 'Repeating Event',start: new Date(y, m, d + 4, 16, 0),allDay: false},
//      {title: 'Birthday Party',start: new Date(y, m, d + 1, 19, 0),end: new Date(y, m, d + 1, 22, 30),allDay: false},
//      {title: 'Click for Google',start: new Date(y, m, 28),end: new Date(y, m, 29),url: 'http://google.com/'}
    ];
    /* event source that calls a function on every view switch */
    $scope.eventsF = function (start, end, timezone, callback) {
      var s = new Date(start).getTime() / 1000;
      var e = new Date(end).getTime() / 1000;
      var m = new Date(start).getMonth();
      var events = [{title: 'Feed Me ' + m,start: s + (50000),end: s + (100000),allDay: false, className: ['customFeed']}];
      callback(events);
    };

//    $scope.calEventsExt = {
//       color: '#f00',
//       textColor: 'yellow',
//       events: [ 
//          {type:'party',title: 'Lunch',start: new Date(y, m, d, 12, 0),end: new Date(y, m, d, 14, 0),allDay: false},
//          {type:'party',title: 'Lunch 2',start: new Date(y, m, d, 12, 0),end: new Date(y, m, d, 14, 0),allDay: false},
//          {type:'party',title: 'Click for Google',start: new Date(y, m, 28),end: new Date(y, m, 29),url: 'http://google.com/'}
//        ]
//    };
    /* alert on eventClick */
    $scope.alertOnEventClick = function( date, jsEvent, view){
        $scope.alertMessage = (date.title + ' was clicked ');
    };
    /* alert on Drop */
     $scope.alertOnDrop = function(event, delta, revertFunc, jsEvent, ui, view){
       $scope.alertMessage = ('Event Droped to make dayDelta ' + delta);
    };
    /* alert on Resize */
    $scope.alertOnResize = function(event, delta, revertFunc, jsEvent, ui, view ){
       $scope.alertMessage = ('Event Resized to make dayDelta ' + delta);
    };
    /* add and removes an event source of choice */
    $scope.addRemoveEventSource = function(sources,source) {
      var canAdd = 0;
      angular.forEach(sources,function(value, key){
        if(sources[key] === source){
          sources.splice(key,1);
          canAdd = 1;
        }
      });
      if(canAdd === 0){
        sources.push(source);
      }
    };
    /* add custom event*/
    $scope.addShifts = function(days) {
//			console.log("num of day in month: " + d);
//			console.log("day in week " + day.id);
//			console.log("next sunday is on: " + nextSunday);
			var $day = nextSunday;
			for(var $i = 0; $i < days.length; $i++){
				if(days[$i].morning){
					$scope.events.push({
						title: days[$i].morning,
						color: '#8cd802',
						start: new Date(y, m, $day , 09, 00),
						end: new Date(y, m, $day, 17, 00),
						className: ['Morning Shift']
					});
				}
				if(days[$i].evening){
					$scope.events.push({
						title: days[$i].evening,
						color: '#dc8800',
						start: new Date(y, m, $day , 16, 00),
						end: new Date(y, m, $day, 24, 00),
						className: ['Evening Shift']
					});
				}
				if(days[$i].night){
					$scope.events.push({
						title: days[$i].night,
						color: '#4000dc',
						start: new Date(y, m, $day+1 , 00, 00),
						end: new Date(y, m, $day+1, 08, 00),
						className: ['Night Shift']
					});
				}
				$day++;
			}
    };
    /* remove event */
    $scope.remove = function(day, element) {
//			console.log(days);
			console.log(day);
			console.log(element);
			console.log(this);
      $scope.events.splice(this,1);
    };
		/* remove all events */
    $scope.removeAll = function() {
      $scope.events.splice(0, $scope.events.length);
    };
    /* Change View */
    $scope.changeView = function(view,calendar) {
      uiCalendarConfig.calendars[calendar].fullCalendar('changeView',view);
    };
    /* Change View */
    $scope.renderCalender = function(calendar) {
      if(uiCalendarConfig.calendars[calendar]){
        uiCalendarConfig.calendars[calendar].fullCalendar('render');
      }
    };
		$scope.test = function(id){
			console.log(id);
			console.log(this);
		}
     /* Render Tooltip */
    $scope.eventRender = function(event, element, view) {
        element.attr({'uib-tooltip-template': "'pages/operators/partials/calendarTooltip.html'",
//											'tooltip-popup-close-delay': '2000',
                     'uib-tooltip-append-to-body': true,
											'ng-model': 'days[element.id-1]',
//											'tooltip-trigger': "'mouseleave'",
											'ng-click': "test("+element.id+")"
										 });
        $compile(element)($scope);
    };
    /* config object */
    $scope.uiConfig = {
      calendar:{
        height: 640,
        editable: true,
        header:{
          left: 'title',
          center: '',
          right: 'today prev,next'
        },
				timeFormat: 'HH:mm',
				displayEventEnd: true,
        eventClick: $scope.alertOnEventClick,
        eventDrop: $scope.alertOnDrop,
        eventResize: $scope.alertOnResize,
        eventRender: $scope.eventRender
      }
    };

    /* event sources array*/
    $scope.eventSources = [$scope.events, $scope.eventSource, $scope.eventsF];
    $scope.eventSources2 = [$scope.calEventsExt, $scope.eventsF, $scope.events];
}
//	} else {
//		var message = 'Please Login first!';
//		var id = Flash.create('danger', message, 3500);
//		$location.path('/');
}]);