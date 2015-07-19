"use strict";

chatApp.controller('listCtrl', ['pollingService', 'messageService', '$http', '$scope', function(pollingService, messageService, $http, $scope){
    $scope.superMsgList = messageService.list;

    function poll_messages(){
        messageService.update(0);
    }

    pollingService.callFnOnInterval(poll_messages, 1500);

    $scope.$watch(
    function(){ //check if changed function
        if(messageService.list.length > 0){
            var first_index = messageService.list.length - 1;
            var second_index = messageService.list[first_index].length - 1;
            return messageService.list[first_index][second_index].id; //return the id of the last message for comparison
        }
        return messageService.list;
    }
    , function(newValue, oldValue){ //what to do if it has changed
        $scope.superMsgList = messageService.list;
    });
}]);
