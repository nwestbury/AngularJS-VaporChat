"use strict";

chatApp.factory('loginService', ['$http', '$location', 'sessionService', function($http, $location, sessionService){
    return {
        login:function(user, scope){
            sessionService.destroy('uid');
            var $promise = $http.post('php/login.php', user); // send login info
            
            $promise.then(function(response){
                var uid = response.data;

                if(uid && (uid.lastIndexOf("vc", 0) === 0)){
                    sessionService.set('uid', uid);
                    $location.path('/home');
                }else{
                    console.log(uid);
                    scope.errormsg = uid;
                }
            });
        },
        logout:function(){
            sessionService.destroy('uid');
            $http.post('php/destroy_session.php');
            $location.path('/login');
        },
        islogged:function(){
            var $checkSessionServer = $http.post('php/check_session.php');
            return $checkSessionServer;
        }
    }
}]);
