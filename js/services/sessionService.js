//"use strict";

chatApp.factory('sessionService', ['$http', function($http){
    return {
        set:function(key, value){
            return sessionStorage.setItem(key, value);
        },
        get:function(key){
            return sessionStorage.getItem(key);
        },
        destroy:function(key){
            if(sessionStorage.getItem(key)){
                $http.post('php/destroy_session.php');
                return sessionStorage.removeItem(key);
            }
        }
    };
}]);
