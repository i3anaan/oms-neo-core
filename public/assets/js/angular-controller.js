/*
Template Name: Color Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3.3.6
Version: 2.0.0
Author: Sean Ngu
Website: http://www.seantheme.com/color-admin-v2.0/admin/angularjs/
    ----------------------------
        APPS CONTROLLER TABLE
    ----------------------------
    1.0 CONTROLLER - App
    2.0 CONTROLLER - Sidebar
    3.0 CONTROLLER - Right Sidebar
    4.0 CONTROLLER - Header
    5.0 CONTROLLER - Top Menu
    6.0 CONTROLLER - Page Loader
    7.0 CONTROLLER - Theme Panel
    8.0 CONTROLLER - Dashboard v1
    9.0 CONTROLLER - Dashboard v2
   10.0 CONTROLLER - Email Inbox v1
   11.0 CONTROLLER - Email Inbox v2
   12.0 CONTROLLER - Email Compose
   13.0 CONTROLLER - Email Detail
   14.0 CONTROLLER - UI Modal & Notifications
   15.0 CONTROLLER - UI Tree
   16.0 CONTROLLER - UI Language Bar
   17.0 CONTROLLER - Form Plugins
   18.0 CONTROLLER - Form Slider + Switcher
   19.0 CONTROLLER - Form Validation
   20.0 CONTROLLER - Table Manage Default
   21.0 CONTROLLER - Table Manage Autofill
   22.0 CONTROLLER - Table Manage Buttons
   23.0 CONTROLLER - Table Manage ColReorder
   24.0 CONTROLLER - Table Manage Fixed Columns
   25.0 CONTROLLER - Table Manage Fixed Header
   26.0 CONTROLLER - Table Manage KeyTable
   27.0 CONTROLLER - Table Manage Responsive
   28.0 CONTROLLER - Table Manage RowReorder
   29.0 CONTROLLER - Table Manage Scroller
   30.0 CONTROLLER - Table Manage Select
   31.0 CONTROLLER - Table Manage Extension Combination
   32.0 CONTROLLER - Flot Chart
   33.0 CONTROLLER - Morris Chart
   34.0 CONTROLLER - Chart JS
   35.0 CONTROLLER - Chart d3
   36.0 CONTROLLER - Calendar
   37.0 CONTROLLER - Vector Map
   38.0 CONTROLLER - Google Map
   39.0 CONTROLLER - Gallery V1
   40.0 CONTROLLER - Gallery V2
   41.0 CONTROLLER - Page with Footer
   42.0 CONTROLLER - Page without Sidebar
   43.0 CONTROLLER - Page with Right Sidebar
   44.0 CONTROLLER - Page with Minified Sidebar
   45.0 CONTROLLER - Page with Two Sidebar
   46.0 CONTROLLER - Full Height Content
   47.0 CONTROLLER - Page with Wide Sidebar
   48.0 CONTROLLER - Page with Light Sidebar
   49.0 CONTROLLER - Page with Mega Menu
   50.0 CONTROLLER - Page with Top Menu
   51.0 CONTROLLER - Page with Boxed Layout
   52.0 CONTROLLER - Page with Mixed Menu
   53.0 CONTROLLER - Page Boxed Layout with Mixed Menu
   54.0 CONTROLLER - Page with Transparent Sidebar
   55.0 CONTROLLER - Timeline
   56.0 CONTROLLER - Coming Soon
   57.0 CONTROLLER - 404 Error
   58.0 CONTROLLER - Login V1
   59.0 CONTROLLER - Login V2
   60.0 CONTROLLER - Login V3
   61.0 CONTROLLER - Register V3
    <!-- ======== GLOBAL SCRIPT SETTING ======== -->
*/


var blue		= '#348fe2',
    blueLight	= '#5da5e8',
    blueDark	= '#1993E4',
    aqua		= '#49b6d6',
    aquaLight	= '#6dc5de',
    aquaDark	= '#3a92ab',
    green		= '#00acac',
    greenLight	= '#33bdbd',
    greenDark	= '#008a8a',
    orange		= '#f59c1a',
    orangeLight	= '#f7b048',
    orangeDark	= '#c47d15',
    dark		= '#2d353c',
    grey		= '#b6c2c9',
    purple		= '#727cb6',
    purpleLight	= '#8e96c5',
    purpleDark	= '#5b6392',
    red         = '#ff5b57';


/* -------------------------------
   1.0 CONTROLLER - App
------------------------------- */
omsApp.controller('appController', ['$rootScope', '$scope', function($rootScope, $scope) {
    $scope.$on('$includeContentLoaded', function() {
        handleSlimScroll();
    });
    $scope.$on('$viewContentLoaded', function() {
    });
    $scope.$on('$stateChangeStart', function() {
        // reset layout setting
        $rootScope.setting.layout.pageSidebarMinified = false;
        $rootScope.setting.layout.pageFixedFooter = false;
        $rootScope.setting.layout.pageRightSidebar = false;
        $rootScope.setting.layout.pageTwoSidebar = false;
        $rootScope.setting.layout.pageTopMenu = false;
        $rootScope.setting.layout.pageBoxedLayout = false;
        $rootScope.setting.layout.pageWithoutSidebar = false;
        $rootScope.setting.layout.pageContentFullHeight = false;
        $rootScope.setting.layout.pageContentFullWidth = false;
        $rootScope.setting.layout.paceTop = false;
        $rootScope.setting.layout.pageLanguageBar = false;
        $rootScope.setting.layout.pageSidebarTransparent = false;
        $rootScope.setting.layout.pageWideSidebar = false;
        $rootScope.setting.layout.pageLightSidebar = false;
        $rootScope.setting.layout.pageFooter = false;
        $rootScope.setting.layout.pageMegaMenu = false;
        $rootScope.setting.layout.pageWithoutHeader = false;
        $rootScope.setting.layout.pageBgWhite = false;
        $rootScope.setting.layout.pageContentInverseMode = false;
        $rootScope.setting.layout.notifications = false;
        
        App.scrollTop();
        $('.pace .pace-progress').addClass('hide');
        $('.pace').removeClass('pace-inactive');
    });
    $scope.$on('$stateChangeSuccess', function() {
        Pace.restart();
        App.initPageLoad();
        App.initSidebarSelection();
        App.initSidebarMobileSelection();
        setTimeout(function() {
            App.initLocalStorage();
            App.initComponent();
        },0);
    });
    $scope.$on('$stateNotFound', function() {
        Pace.stop();
    });
    $scope.$on('$stateChangeError', function() {
        Pace.stop();
    });
}]);



/* -------------------------------
   2.0 CONTROLLER - Sidebar
------------------------------- */
omsApp.controller('sidebarController', function($scope, $rootScope, $state) {
    App.initSidebar();
    var vm = this;
    vm.goToLink = function(url) {
        location.href = url;
    }

    vm.user = $rootScope.currentUser;
});


/* -------------------------------
   4.0 CONTROLLER - Header
------------------------------- */
omsApp.controller('headerController', function($rootScope, $state, $http) {
    var vm = this;
    vm.user = $rootScope.currentUser;
    vm.notifications_enabled = true;

    vm.markRead = (notification) => {
      if(vm.notifications) {

        vm.notifications.forEach((notification) => {
          if(notification.read)
            return;

          $http({
            url: '/services/oms-notification-onscreen/api/notifications/' + notification.id,
            method: 'PUT',
            data: {
              read: true
            }
          }).then((response) => {
            // do nothing...
          }).catch((error) => {
            console.log(error);
          });
        });
        vm.unreadCount = 0;
      }

      vm.notifications_enabled = true;
      vm.getNotifications();
    }

    vm.goToLink = (notification) => {
      if(notification.heading_link) {
        $state.go(notification.heading_link, notification.heading_link_params);
      }
    } 

    vm.getNotifications = () => {
      if(!vm.notifications_enabled || vm.busy)
        return;

      vm.busy = true;
      $http({
        url: '/services/oms-notification-onscreen/api/notifications',
        method: 'GET'
      }).then((response) => {
        vm.busy = false;
        if(response && response.data && response.data.data && response.data.success) {
          vm.notifications = response.data.data;
          vm.unreadCount = vm.notifications.reduce((acc, cur) => {
            if(cur.read)
              return acc;
            return acc + 1;
          }, 0);
        } else {
          vm.notifications_enabled = false;
        }
      }).catch((error) => {
        vm.busy = false;
        showError(error);
        vm.notifications_enabled = false;
      });
    }
    if($rootScope.notification_update_interval) { // For some reason the controller code gets called twice
      clearInterval($rootScope.notification_update_interval);
    }
    vm.getNotifications();
    $rootScope.notification_update_interval = setInterval(vm.getNotifications, 60000);
  

    vm.logout = function() {
      var token = window.localStorage.getItem("X-Auth-Token");
      window.localStorage.removeItem("X-Auth-Token");
      $rootScope.currentUser = undefined;
      $http({
          method: 'POST',
          url: '/api/logout'
      }).then((result) => {
        $state.go('public.welcome');
        //window.location.reload();
      }).catch((err) => {
        $state.go('public.welcome');
        //window.location.reload();
      })
    }
});


