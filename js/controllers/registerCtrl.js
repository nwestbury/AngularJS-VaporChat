"use strict";

chatApp.controller('registerCtrl', ['$scope', '$http', '$timeout', 'loginService', 'pageService', 'Upload', function($scope, $http, $timeout, loginService, pageService, Upload){
    pageService.set_page_name("Register Page");
    pageService.set_show_options(false);

    $scope.register = function (users, files){
        console.log("SEND THIS", users, files);

        if(files != null && files.length > 0){
            var icon = files[0];
            icon.upload = Upload.upload({
                url: 'php/upload.php',
                method: 'POST',
                fields: users,
                file: icon,
                fileFormDataName: 'icon'
            });

            icon.upload.success(function(response){
                $scope.successmsg = "";
                if(response.lastIndexOf("Suc", 0) === 0){
                    $scope.successmsg = response;
                }else{
                    $scope.errormsg = response;
                }
            });

            icon.upload.error(function(response){
                $scope.errormsg = "Unknown error, try again later.";
            });

            icon.upload.progress(function (evt) {
                // Math.min is to fix IE which reports 200% sometimes
                icon.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            });
        }
    }


}]);
