angular.module('starter.controllers', [])

// 首页
.controller('DashCtrl', function($scope, $state, article) {
    $scope.$on('$ionicView.beforeEnter', function() {
        $scope.page = 1;
        $scope.hasMoreData = false;
        $scope.list = []
        article.getList($scope.page, function(data) {
            if (data.status == 200) {
                $scope.list = data.data
                $scope.hasMoreData = true
            }
        })
    })

    $scope.loadMore = function() {
        if ($scope.hasMoreData) {
            $scope.page++
                article.getList($scope.page, function(data) {
                    if (data.status == 200) {
                        var length = data.data.length
                        if (length != 0) {
                            for (var i = 0; i < length; i++) {
                                $scope.list.push(data.data[i])
                            }
                        } else {
                            $scope.hasMoreData = false;
                        }
                    }
                })
            $scope.$broadcast('scroll.infiniteScrollComplete');
        }
    }
    $scope.goToArticleDetail = function(id) {
        $state.go('tab.article-detail', { id: id })
    }
})

// 个人中心
.controller('AccountCtrl', function($scope) {

    })
    .controller('ArticleDetailCtrl', function($scope, article, $stateParams, $ionicLoading) {
        $scope.$on('$ionicView.beforeEnter', function() {
            var id = $stateParams.id
            article.detail(id, function(data) {
                if (data.status == 200) {
                    $scope.data = data.data
                }
            })
        })
        $scope.favourite = function(id) {
            article.attention(id, function(data) {
                if (data.status == 200) {
                    $scope.data.attention = $scope.data.attention == 1 ? 0 : 1;
                } else {
                    $ionicLoading.show({
                        template: data.error,
                        noBackdrop: true,
                        duration: 1000
                    })
                }
            })
        }
    })

    // 关注的文章
    .controller('LikeCtrl', function($scope, attention, $state, $ionicLoading) {
        $scope.$on('$ionicView.beforeEnter', function() {
            $scope.page = 1;
            $scope.hasMoreData = false;
            $scope.list = [];

            attention.getList($scope.page, function(data) {
                if (data.status == 200) {
                    $scope.list = data.data;
                    $scope.hasMoreData = true;
                } else {
                    $ionicLoading.show({
                        template: data.error,
                        noBackdrop: true,
                        duration: 1000
                    })
                }
            })
        })

        $scope.loadMore = function() {
            if ($scope.hasMoreData) {
                $scope.page++
                    attention.getList($scope.page, function(data) {
                        if (data.status == 200) {
                            var length = data.data.length
                            if (length != 0) {
                                for (var i = 0; i < length; i++) {
                                    $scope.list.push(data.data[i])
                                }
                            } else {
                                $scope.hasMoreData = false;
                            }
                        }
                    })
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
        }


        $scope.goToArticleDetail = function(id) {
            $state.go('tab.article-detail', { id: id })
        }
    })
