"use strict";

chatApp.controller('roomCtrl', ['$scope', '$http', '$routeParams', '$location', 'loginService', 'pageService', 'messageService', function($scope, $http, $routeParams, $location, loginService, pageService, messageService){
    pageService.set_page_name("Chat Room: " + $routeParams.user);
    pageService.set_show_options(0b1111);
    
    var users = [$routeParams.user];
    var request = $http.post('php/valid_room.php', {users: users});

    request.success(function(data, status){
        if(data){
            messageService.set_list(data);
            messageService.set_users(users);
        }else{
            $location.path('/home');
        }
    });
}]);

