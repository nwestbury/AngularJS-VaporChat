"use strict";

chatApp.factory('pollingService', ['$timeout', function($timeout){
   function callFnOnInterval(fn, timeInterval) {

       var promise = $timeout(fn, timeInterval);

       return promise.then(function(){
           callFnOnInterval(fn, timeInterval);
       });
   };

   return {
       callFnOnInterval: callFnOnInterval
   };
}]);

