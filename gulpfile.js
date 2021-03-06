process.env.DISABLE_NOTIFIER = true;
var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
  mix.styles([
    '../../../public/assets/plugins/bootstrap/css/bootstrap.min.css',
    '../../../public/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css',
    '../../../public/assets/plugins/font-awesome/css/font-awesome.min.css',
    '../../../public/assets/css/animate.min.css',
    '../../../public/assets/css/style.css',
    '../../../public/assets/css/style-responsive.min.css',
    '../../../public/assets/css/theme/default.css',
    '../../../public/assets/plugins/gritter/css/jquery.gritter.css',
    '../../../public/assets/plugins/select2/dist/css/select2.min.css',
    '../../../public/assets/plugins/ionicons/css/ionicons.min.css',
    '../../../public/assets/plugins/bootstrap-wizard/css/bwizard.min.css',
    '../../../public/assets/plugins/bootstrap-datepicker/css/datepicker.css',
    '../../../public/assets/plugins/bootstrap-datepicker/css/datepicker3.css',
    '../../../public/assets/plugins/parsley/src/parsley.css',
    '../../../public/assets/plugins/bootstrap-eonasdan-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
    '../../../node_modules/angular-bootstrap-datetimepicker/src/css/datetimepicker.css',
    '../../../node_modules/angularjs-slider/dist/rzslider.css',
    '../../../node_modules/ng-switchery/dist/switchery.min.css',
    '../otherLibs/nestable/style.css',
    '../../../public/assets/css/own.css'
  ],
  'public/vendor/vendor.css');

  mix.scripts([
    '../../../public/assets/plugins/pace/pace.min.js',
    '../../../public/assets/plugins/angularjs/angular.min.js',
    '../../../public/assets/plugins/angularjs/angular-ui-route.min.js',
    '../../../public/assets/plugins/bootstrap-angular-ui/ui-bootstrap-tpls.min.js',
    '../../../public/assets/plugins/angularjs/ocLazyLoad.min.js',
    '../../../public/assets/plugins/jquery/jquery-1.9.1.min.js',
    '../../../public/assets/plugins/jquery/jquery-migrate-1.1.0.min.js',
    '../../../public/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js',
    '../../../public/assets/plugins/bootstrap/js/bootstrap.min.js',
    '../../../public/assets/plugins/slimscroll/jquery.slimscroll.min.js',
    '../../../public/assets/plugins/jquery-cookie/jquery.cookie.js',
    '../../../public/assets/plugins/gritter/js/jquery.gritter.js',
    '../../../public/assets/plugins/select2/dist/js/select2.min.js',
    '../../../public/assets/plugins/bootstrap-wizard/js/bwizard.js',
    '../../../public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
    '../../../public/assets/plugins/bootstrap-daterangepicker/moment.js',
    '../../../public/assets/plugins/parsley/dist/parsley.js',
    '../../../public/assets/plugins/bootstrap-eonasdan-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
    '../bower_components/angular-file-upload/dist/angular-file-upload.min.js',
    '../../../node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.js',
    '../../../node_modules/angular-bootstrap-datetimepicker/src/js/datetimepicker.templates.js',
    '../../../node_modules/ng-infinite-scroll/build/ng-infinite-scroll.js',
    '../../../node_modules/angular-bootstrap3-typeahead/angular-bootstrap3-typeahead.js',
    '../../../node_modules/angularjs-slider/dist/rzslider.js',
    '../../../node_modules/angular-timeago/dist/angular-timeago.js',
    '../../../node_modules/ng-switchery/dist/ng-switchery.js',
    '../bower_components/showdown/compressed/Showdown.min.js',
    '../bower_components/angular-markdown-directive/markdown.js',
    '../otherLibs/nestable/jquery.nestable.js',
    '../otherLibs/nestable/jquery.nestable++.js',
    '../bower_components/angucomplete-alt/dist/angucomplete-alt.min.js'
  ],
  'public/vendor/vendor.js');

  mix.scripts([
    '../../../public/assets/crossbrowserjs/excanvas.min.js',
    '../../../public/assets/crossbrowserjs/html5shiv.js',
    '../../../public/assets/crossbrowserjs/respond.min.js'

  ],
  'public/vendor/crossbrowser.js');

  mix.scripts([
    '../../../public/assets/js/angular-setting.js',
    '../../../public/assets/js/angular-controller.js',
    '../../../public/assets/js/angular-directive.js',
    '../bower_components/angular-sanitize/angular-sanitize.min.js',
    '../../../public/assets/js/apps.js',
    '../../../public/assets/js/own.js'
  ],
  'public/vendor/template.js');
});
