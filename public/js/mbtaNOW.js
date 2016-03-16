var mbtaNOW = angular.module("mbtaNOW", ["ngAnimate", "ngRoute"]);

mbtaNOW.config(function($routeProvider, $locationProvider) {
    $routeProvider.when("/Dashboard", {
       templateUrl: "pages/indexBody.html"
    })
    .when("/BlueLine", {
        templateUrl: "pages/blueLine.html",
        controller: "BlueLineController"
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

    $locationProvider.html5Mode(true);
});

mbtaNOW.controller("BlueLineController", function($scope, $http) {
    $scope.schedule = [];
    
    getRouteData($scope, $http, "blue", "");
});

mbtaNOW.controller("GreenLineController", function($scope, $http) {
    $scope.schedule = [];
    var route = "green";
    var line = (window.location.hash) ? window.location.hash.replace("#", "") : "b";

    $(".tabs").tabs();

    $(".tabs a").click(function() {
        line = $(this).attr("href").replace("#", "");
        getRouteData($scope, $http, route, line);
    });

    getRouteData($scope, $http, route, line);
});

mbtaNOW.controller("OrangeLineController", function($scope, $http) {
    $scope.schedule = [];
    
    getRouteData($scope, $http, "orange", "");
});

mbtaNOW.controller("RedLineController", function($scope, $http) {
    $scope.schedule = [];
    var route = "red";
    var line = (window.location.hash) ? window.location.hash.replace("#", "") : "ashmont";

    $(".tabs").tabs();

    $(".tabs a").click(function() {
        line = $(this).attr("href").replace("#", "");
        getRouteData($scope, $http, route, line);
    });

    getRouteData($scope, $http, route, line);
});

mbtaNOW.controller("SilverLineController", function($scope, $http) {
    $scope.schedule = [];
    var route = "silver";
    var line = (window.location.hash) ? window.location.hash.replace("#", "") : "sl1";

    $(".tabs").tabs();

    $(".tabs a").click(function() {
        line = $(this).attr("href").replace("#", "");
        getRouteData($scope, $http, route, line);
    });

    getRouteData($scope, $http, route, line);
});

var getRouteData = function(scope, http, route, line) {
    if (line !== "") {
        line = "&line=" + line;
    }

    http.get("request.php?route=" + route + line).success(function(response) {
        scope.schedule = response;
    });
};