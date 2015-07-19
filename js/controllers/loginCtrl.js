"use strict";

chatApp.controller('loginCtrl', function($scope, loginService, pageService){
    pageService.set_page_name("Login Page");
    pageService.set_show_options(false);

    $scope.errormsg = '';
    $scope.login = function(user){
        loginService.login(user, $scope);
    }

});
