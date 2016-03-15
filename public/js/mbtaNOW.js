var mbtaNOW = angular.module("mbtaNOW", ["ngAnimate", "ngRoute"]);

mbtaNOW.config(function($routeProvider, $locationProvider) {
    $routeProvider.when("/Dashboard", {
       templateUrl: "pages/indexBody.html"
    })
    .when("/GreenLine", {
        templateUrl: "pages/greenLine.html",
        controller: "greenLineController"
     })
/*    .otherwise({
        redirectTo: "/Dashboard"
    });*/

    $locationProvider.html5Mode(true);
});

mbtaNOW.controller("greenLineController", function($scope, $http){
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

var getRouteData = function(scope, http, route, line) {
    if (line !== "") {
        line = "/" + line;
    }

    http.get("request.php?route=" + route + line).success(function(response) {
        scope.schedule = response;
    });
};