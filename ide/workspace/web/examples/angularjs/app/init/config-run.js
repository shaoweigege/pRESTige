app.factory('httpRequestInterceptor', function ($rootScope) {
    return {
        request: function (config) {
            if ($rootScope.currentUser) {
                config.headers['api-key'] = $rootScope.currentUser.token;
                
                if($rootScope.SETTINGS.enableSaaS){
                    if(config.method == "GET" || config.method == "DELETE"){
                        var secret = '/?secret=';
                        console.log(config.url);
                        if(config.url.endsWith('/')) secret = '?secret=';
                        if(config.url.indexOf('?') > -1) secret = '&secret=';
                        config.url = config.url + secret + $rootScope.currentUser.secret;
                    }
                    else{
                        config.data.secret = $rootScope.currentUser.secret;
                    }
                }
            }
            return config;
        }
    };
});

function CustomRoutes(){
    this.routes = RegisterRoutes();
}

app.provider('customRoutes', function() {
    Object.assign(this, new CustomRoutes());

    this.$get = function() {
        return new CustomRoutes();
    };
});

app.config(function ($routeProvider, $resourceProvider, $httpProvider, customRoutesProvider) {
    var routes = customRoutesProvider.routes;
    for (var i = 0; i < routes.length; i++) {
        var r = routes[i];
        $routeProvider.when('/' + r.route, { templateUrl: 'app/modules/' + r.template + '.html', controller: r.controller + 'Controller'});
    }

    $httpProvider.interceptors.push('httpRequestInterceptor');
});

app.run(function ($rootScope, $location, $cookies, H) {
    $rootScope.SETTINGS = H.SETTINGS;

    $rootScope.fieldTypes = H.SETTINGS.fieldTypes;

    $rootScope.$on("$locationChangeStart", function (event, next, current) {
        if (!$rootScope.currentUser) {
            var cookie = $cookies.get(H.getCookieKey());
            if (!cookie) {
                $location.path('/sign-in');
            }
            else {
                var cu = JSON.parse(cookie);
                $rootScope.currentUser = typeof cu==='string'? JSON.parse(cu):cu;
            }
        }
    });
});

