module.exports = function(grunt) {
  grunt.initConfig({
    pkg: '<json:package.json>',
    meta: {
      wpStyleHeader: '/*\n' +
        ' Theme Name: Ally\n' +
        ' Theme URI: http://www.axisphilly.com\n' +
        ' Description: Custom theme for AxisPhilly\n' +
        ' Author: Casey Thomas, Jeff Frankl\n' +
        ' Author URI: caseypthomas.org, jfrankl.org\n' +
        ' Version: <%= pkg.version %>\n' +
        '*/',
      wpPhpHeader: '<?php /*!\n' +
        '* @package WordPress\n' +
        '* @subpackdefault=datetime.date.today)age Ally\n' +
        '*/\n' +
        '?>\n'
    },
    lint: {
      files: ['javascripts/site.js']
    },
    concat: {
      foundation: {
        src: [
          'javascripts/foundation/jquery.cookie.js',
          'javascripts/foundation/jquery.event.move.js',
          'javascripts/foundation/jquery.event.swipe.js',
          'javascripts/foundation/jquery.foundation.accordian.js',
          'javascripts/foundation/jquery.foundation.alerts.js',
          'javascripts/foundation/jquery.foundation.buttons.js',
          'javascripts/foundation/jquery.foundation.clearing.js',
          'javascripts/foundation/jquery.foundation.forms.js',
          'javascripts/foundation/jquery.foundation.joyride.js',
          'javascripts/foundation/jquery.foundation.magellan.js',
          'javascripts/foundation/jquery.foundation.mediaQueryToggle.js',
          'javascripts/foundation/jquery.foundation.navigation.js',
          'javascripts/foundation/jquery.foundation.orbit.js',
          'javascripts/foundation/jquery.foundation.reveal.js',
          'javascripts/foundation/jquery.foundation.tabs.js',
          'javascripts/foundation/jquery.foundation.tooltips.js',
          'javascripts/foundation/jquery.foundation.topbar.js',
          'javascripts/foundation/jquery.foundation.offcanvas.js',
          'javascripts/foundation/jquery.foundation.placeholder.js',
          'javascripts/foundation/app.js'
        ],
        dest: 'javascripts/foundation.js'
      },
      libraries: {
        src: 'javascripts/contrib/*',
        dest: 'javascripts/libraries.<%= pkg.version %>.min.js'
      },
        sass: {
        src: [
          '<banner:meta.wpStyleHeader>',
          'stylesheets/app.css',
          'stylesheets/function-overrides.css',
          'stylesheets/compass.css'
        ],
        dest: 'style.css'
      }
    },
    min: {
      site: {
        src: 'javascripts/site.js',
        dest: 'javascripts/site.<%= pkg.version %>.min.js'
      },
      foundation: {
        src: 'javascripts/foundation.js',
        dest: 'javascripts/foundation.min.js'
      }
    },
    /*compass: {
      dist: {
        options: {
          sassDir: 'sass',
          cssDir: ''
        }
      }
    }*/
    compress: {
      tar: {
        files: {
          'ally.<%= pkg.version %>.tar': ['style.css', '*.php']
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-compress');

  grunt.registerTask('default', 'concat lint min');
  grunt.registerTask('release', 'compress');
};
