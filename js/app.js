"use strict";

var chatApp = angular.module("chatApp", ['ngRoute', 'ui.bootstrap', 'luegg.directives', 'ngFileUpload']);

chatApp.config(['$routeProvider', function($routeProvider){
    $routeProvider.when('/login', {
                                       title:      'Login Page',  
                                       templateUrl:'partials/login.html',
                                       controller: 'loginCtrl', 
                                  });
    $routeProvider.when('/register', {
                                       title:      'Register Page',  
                                       templateUrl:'partials/register.html',
                                       controller: 'registerCtrl', 
                                  });
    $routeProvider.when('/home', {
                                      title:      'Home Page',  
                                      templateUrl:'partials/home.html',
                                      controller: 'homeCtrl'
                                 });
    $routeProvider.when('/room/:user', {
                                      title:      'Chat Room',  
                                      templateUrl:'partials/room.html',
                                      controller: 'roomCtrl'
                                 });
    $routeProvider.otherwise({redirectTo: '/login'});
}]);

chatApp.run(function($rootScope, $location, loginService){
    var routespermission = ['/home', '/room'];
    $rootScope.$on('$routeChangeStart', function(){
        if(routespermission.indexOf($location.path()) != -1){
            var connected = loginService.islogged();
            connected.then(function(response){
                if(!response.data)
                    $location.path('/login');
            });
        }
    });
    $rootScope.$on('$routeChangeSuccess', function (event, current, previous) {
        if (typeof current.$$route !== 'undefined' && typeof current.$$route.title !== 'undefined')
            $rootScope.title = current.$$route.title;
    });
});
