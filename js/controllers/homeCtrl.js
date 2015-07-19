"use strict";

chatApp.controller('homeCtrl', ['$scope', '$http', 'loginService', 'pageService', function($scope, $http, loginService, pageService){
    pageService.set_page_name("Home Page");
    pageService.set_show_options(0b11101);
    update_table();

    function update_table(){
        console.log("UPDATE TABLE");
        var request = $http.post('php/chat_table.php');
        request.success(function(data, status){
            $scope.tableData = data;
        });
    }

    $scope.$on('tableUpdateNeeded', update_table);
}]);
