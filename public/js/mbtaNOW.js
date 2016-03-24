var mbtaNOW = angular.module("mbtaNOW", ["ngAnimate", "ngRoute"]);

mbtaNOW.config(function($routeProvider, $locationProvider) {
    $routeProvider.when("/Dashboard", {
       templateUrl: "pages/indexBody.html"
    })
    .when("/BlueLine", {
        templateUrl: "pages/blueLine.html",
        controller: "BlueLineController"
    })
    .when("/CommuterRail", {
        templateUrl: "pages/commuterRail.html",
        controller: "CommuterRailController"
    })
    .when("/GreenLine", {
        templateUrl: "pages/greenLine.html",
        controller: "GreenLineController"
     })
    .when("/OrangeLine", {
        templateUrl: "pages/orangeLine.html",
        controller: "OrangeLineController"
    })
    .when("/RedLine", {
        templateUrl: "pages/redLine.html",
        controller: "RedLineController"
    })
    .when("/SilverLine", {
        templateUrl: "pages/silverLine.html",
        controller: "SilverLineController"
    })
    .otherwise({
        redirectTo: "/Dashboard"
    });

    $locationProvider.html5Mode({
         enabled: true,
         requireBase: false
    });
});

var promise = undefined;

mbtaNOW.controller("BlueLineController", function($scope, $http, $timeout) {
    $scope.schedule = [];

    var request = function() {
        $http.get("request.php?route=blue").success(function(response) {
            $scope.schedule = response;

            promise = $timeout(request, 15000);
        });
    };
    
    request();

    $scope.$on('$destroy', function(){
        if (angular.isDefined(promise)) {
            $timeout.cancel(promise);
            promise = undefined;
        }
    });
});

mbtaNOW.controller("CommuterRailController", function($scope, $http, $timeout) {
    $scope.schedule = [];

    var request = function() {
        $http.get("request.php?route=commuter-rail").success(function(response) {
            $scope.schedule = response;

            promise = $timeout(request, 15000);
        });
    };
    
    request();

    $scope.$on('$destroy', function(){
        if (angular.isDefined(promise)) {
            $timeout.cancel(promise);
            promise = undefined;
        }
    });

    $("select").material_select();
});

mbtaNOW.controller("GreenLineController", function($scope, $http, $timeout) {
    $scope.schedule = [];
    var route = "green";
    var line = (window.location.hash) ? window.location.hash.replace("#", "") : "b";

    $(".tabs").tabs();

    var request = function(line) {
        $http.get("request.php?route=green&line=" + line).success(function(response) {
            $scope.schedule = response;
        });

        promise = $timeout(request, 15000, true, line);
    };

    $(".tabs a").click(function() {
        line = $(this).attr("href").replace("#", "");
        $timeout.cancel(promise);
        request(line);
    });
    
    request(line);

    $scope.$on('$destroy', function(){
        if (angular.isDefined(promise)) {
            $timeout.cancel(promise);
            promise = undefined;
        }
    });
});

mbtaNOW.controller("OrangeLineController", function($scope, $http, $timeout) {
    $scope.schedule = [];
    
    var request = function() {
        $http.get("request.php?route=orange").success(function(response) {
            $scope.schedule = response;

            promise = $timeout(request, 15000);
        });
    };
    
    request();

    $scope.$on('$destroy', function(){
        if (angular.isDefined(promise)) {
            $timeout.cancel(promise);
            promise = undefined;
        }
    });
});

mbtaNOW.controller("RedLineController", function($scope, $http, $timeout) {
    $scope.schedule = [];
    var route = "red";
    var line = (window.location.hash) ? window.location.hash.replace("#", "") : "ashmont";

    $(".tabs").tabs();

    var request = function(line) {
        $http.get("request.php?route=red&line=" + line).success(function(response) {
            $scope.schedule = response;
        });

        promise = $timeout(request, 15000, true, line);
    };

    $(".tabs a").click(function() {
        line = $(this).attr("href").replace("#", "");
        $timeout.cancel(promise);
        request(line);
    });
    
    request(line);

    $scope.$on('$destroy', function(){
        if (angular.isDefined(promise)) {
            $timeout.cancel(promise);
            promise = undefined;
        }
    });
});

mbtaNOW.controller("SilverLineController", function($scope, $http, $timeout) {
    $scope.schedule = [];
    var route = "silver";
    var line = (window.location.hash) ? window.location.hash.replace("#", "") : "sl1";

    $(".tabs").tabs();

    var request = function(line) {
        $http.get("request.php?route=silver&line=" + line).success(function(response) {
            $scope.schedule = response;
        });

        promise = $timeout(request, 15000, true, line);
    };

    $(".tabs a").click(function() {
        line = $(this).attr("href").replace("#", "");
        $timeout.cancel(promise);
        request(line);
    });
    
    request(line);

    $scope.$on('$destroy', function(){
        if (angular.isDefined(promise)) {
            $timeout.cancel(promise);
            promise = undefined;
        }
    });
});