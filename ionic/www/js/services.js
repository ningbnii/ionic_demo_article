angular.module('starter.services', [])

// localstorage
.factory('$localstorage', ['$window', '$rootScope', function($window, $rootScope) {
    return {
        set: function(key, value) {
            $window.localStorage[key + '_' + $rootScope.openid] = value;
        },
        get: function(key, defaultValue) {
            return $window.localStorage[key + '_' + $rootScope.openid] || defaultValue;
        },
        setObject: function(key, value) {
            $window.localStorage[key + '_' + $rootScope.openid] = JSON.stringify(value);
        },
        getObject: function(key) {
            if ($window.localStorage[key + '_' + $rootScope.openid] === undefined) {
                var result = new Array();
                return result;
            } else {
                return JSON.parse($window.localStorage[key + '_' + $rootScope.openid]);
            }
        },
        del: function(key) {
            return $window.localStorage.removeItem(key + '_' + $rootScope.openid);
        }
    }
}])

.factory('article', function($http, apiUrl, $rootScope) {
    return {
        getList: function(page, callback) {
            $http.post(apiUrl + '/article/getList', { openid: $rootScope.openid, page: page }).success(function(data) {
                callback(data);
            });
        },
        detail: function(id, callback) {
            $http.post(apiUrl + '/article/detail', { openid: $rootScope.openid, id: id }).success(function(data) {
                callback(data);
            });
        },
        attention: function(id, callback) {
            $http.post(apiUrl + '/article/attention', { openid: $rootScope.openid, id: id }).success(function(data) {
                callback(data);
            });
        },

    }
})

.factory('attention', function($http, apiUrl, $rootScope) {
    return {
        getList: function(page, callback) {
            $http.post(apiUrl + '/attention/getList', { openid: $rootScope.openid, page: page }).success(function(data) {
                callback(data);
            });
        }
    }
})
