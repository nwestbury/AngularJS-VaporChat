"use strict";

chatApp.controller('pageCtrl', ['$scope', '$rootScope', '$http', '$location', 'pageService', 'loginService', 'messageService', function($scope, $rootScope, $http, $location, pageService, loginService, messageService){
    $scope.title = function(){ return pageService.get_page_name(); };
    $scope.showOptions = function(index){
        return (pageService.get_show_options() >> index) & 1;
    };
    $scope.destroy = function(){
        var users = messageService.users;
        var request = $http.post('php/destroy.php', {users: users});

        request.success(function(data){
            console.log(data);
            if(data){
                var deleted = parseInt(data);
                if(deleted==1){
                    var boom = new Audio('mp3/boom.mp3');
                    boom.play();
                    messageService.set_list([]);                    
                    console.log(deleted);
                }
            }else{
                $location.path('/home');
            }
        });
    }
    $scope.logout = function(){ loginService.logout(); };
    $scope.setstatus = function(number){
        var request = $http.post('php/status.php', {status: number});

        request.success(function(data){
            if(data){
                var new_status = parseInt(data);
                var path = new_status==0 ? 'mp3/chut.mp3' : 'mp3/ello.mp3';

                var audio = new Audio(path);
                audio.play();

                console.log(data);
            }else{
                $location.path('/home');
            }
        });
    };
    $scope.addfriend = function(number){
        var name = prompt("Friend's name?");
        if(name && name.length > 0){
            var request = $http.post('php/addfriend.php', {name: name});
            request.success(function(data){
                if(data.lastIndexOf("Suc", 0) === 0)
                    $rootScope.$broadcast('tableUpdateNeeded');
            });
        }
    };
}]);
