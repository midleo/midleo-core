var app = angular.module('ngApp', []);
app.controller('instCtrl', function($scope, $http) {
  $scope.checkdb = function(){ 
    $http({
      method: 'POST',
      data: { 'db' : $scope.db },
      url: '/apiinst/db/check'
    }).then(function successCallback(response) {
      if(response.data.success==true){
        notify(response.data.info, 'success');
        $("#btn-db-check").hide();
        $("#btn-db-install").show();
        $("#btn-db-install").prop("disabled", false);
      } else {
        notify(response.data.info, 'warning');
      }
    });
  };
});