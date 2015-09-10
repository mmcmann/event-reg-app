app.controller('EventsCtrl', function($scope, $route, $location, $http, Data) {

    Data.get('events/past').then(function(results) { $scope.pastEvents = results; });

    Data.get('events/future').then(function(results) { $scope.futureEvents = results; });

    $scope.register = function (event) {
        Data.post('events/register', {event:event}).then(function(results) {
            Data.toast(results);

            Data.get('events/past').then(function(results) {
                console.log(results);
                // $scope.events = results;
                $scope.pastEvents = results;
            });
        
            Data.get('events/future').then(function(results) {
                $scope.futureEvents = results;
            });
        });
    };

    $scope.cancel = function (event) {
        Data.post('events/cancel', {event:event}).then(function(results) {
            Data.toast(results);
            Data.get('events/past').then(function(results) {
                // console.log(results);
                $scope.pastEvents = results;
            });
        
            Data.get('events/future').then(function(results) {
                $scope.futureEvents = results;
            });
        });
    };

    $scope.logout = function () {
        Data.get('logout').then(function (results) {
            Data.toast(results);
            $location.path('login');
        });
    }

});