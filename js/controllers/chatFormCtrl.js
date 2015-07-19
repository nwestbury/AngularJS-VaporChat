"use strict";

chatApp.controller('chatFormCtrl', ['messageService', function(messageService){
    this.text = "";
    this.addMsg = function(){
        if(this.text.length)
            messageService.update(this.text);
        this.text = "";
    }; 
}]);
