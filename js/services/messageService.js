"use strict";

chatApp.factory('messageService', ['$http', '$location', function($http, $location){
    var messageService = {};

    messageService.list = []; // list will be in the format [[{}], [{}, {}], [{}]] where the array is a message
    messageService.users = []; // list of users who we are communicating with
    
    messageService.set_list = function(msgList){
        messageService.list = msgList;
    };

    messageService.set_users = function(users){
        messageService.users = users;
    };

    messageService.append = function(msg){
        var users = messageService.users; //an array of username strings
        
        var request = $http.post('php/message.php', {users: users, msg: msg}); 

        request.success(function(msgArray, status){
            if(msgArray){
                var lastIndex = messageService.list.length - 1;
                
                for (var index = 0; index < msgArray.length; ++index){
                    var lastName = false;
                    var curName = msgArray[index].name;
                    
                    if(lastIndex >= 0)
                        lastName = messageService.list[lastIndex][0].name;        

                    if(lastName && (lastName == curName)){
                        messageService.list[lastIndex].push(msgArray[index]);
                    }else{
                        messageService.list.push([msgArray[index]]);
                        lastIndex++;
                    }
                }
            }else{
                console.log("FUCK YOU!");
                $location.path('/home');
            }
        });
    };

    return messageService;
}]);
