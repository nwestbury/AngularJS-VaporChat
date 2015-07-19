"use strict";

chatApp.factory('pageService', [function(){
    var title = "Vapor Chat";
    var showOptions = 0; //show options is uses a binary number (0, 0, 0) = Invisible, Online, Logout default = 0
    
    return {
        get_page_name : function(){
            return title;
        },
        set_page_name : function(name){
            title = name;
        },
        get_show_options : function(){
            return showOptions;
        },
        set_show_options : function(bool){
            showOptions = bool;
        },
    };
}]);

