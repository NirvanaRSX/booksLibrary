'use strict';

var libApp = angular.module('libApp', ['angular-loading-bar', 'angularUtils.directives.dirPagination'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[[');
    $interpolateProvider.endSymbol(']]');
});

libApp.controller('booksLib', function booksLib($scope, $http) {
    $http.get('/data').then(function(response) {
        $scope.books = response.data;

        $scope.sort = function(keyname){
            $scope.sortKey = keyname;
            $scope.reverse = !$scope.reverse;
        }

        },function (error){
            console.log(error, 'can not get data.');
        });

    $scope.submit = function(){
        $http.post(Routing.generate('homepage'),this.serializeData($scope.data), {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded' //Fix for Symfony
            }
            })
            .success(function(data) {
                console.log($scope.data)
            })
            .error(function (data){
                $log.error(data);
            });
    };
});

angular.module('booksLib', []).
config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/phones/:phoneId', {
            templateUrl: '../../views/main/edit.html.twig'
        }).
        otherwise({
            redirectTo: '/'
        });
    }]);