module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
      all: ['javascripts/site.js']
    },
    concat: {
      options: {
        banner:  '/*\n' +
          ' Theme Name: Ally\n' +
          ' Theme URI: http://www.axisphilly.com\n' +
          ' Description: Custom theme for AxisPhilly\n' +
          ' Author: Casey Thomas, Jeff Frankl\n' +
          ' Author URI: caseypthomas.org, jfrankl.org\n' +
          ' Version: <%= pkg.version %>\n' +
          '*/\n'
      },
      foundation: {
        src: [
          'javascripts/foundation/jquery.cookie.js',
          'javascripts/foundation/jquery.event.move.js',
          'javascripts/foundation/jquery.event.swipe.js',
          //'javascripts/foundation/jquery.foundation.accordion.js',
          'javascripts/foundation/jquery.foundation.alerts.js',
          'javascripts/foundation/jquery.foundation.buttons.js',
          'javascripts/foundation/jquery.foundation.clearing.js',
          'javascripts/foundation/jquery.foundation.forms.js',
          //'javascripts/foundation/jquery.foundation.joyride.js',
          //'javascripts/foundation/jquery.foundation.magellan.js',
          'javascripts/foundation/jquery.foundation.mediaQueryToggle.js',
          'javascripts/foundation/jquery.foundation.navigation.js',
          'javascripts/foundation/jquery.foundation.orbit.js',
          'javascripts/foundation/jquery.foundation.reveal.js',
          'javascripts/foundation/jquery.foundation.tabs.js',
          'javascripts/foundation/jquery.foundation.tooltips.js',
          'javascripts/foundation/jquery.foundation.topbar.js',
          //'javascripts/foundation/jquery.offcanvas.js',
          'javascripts/foundation/jquery.placeholder.js',
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
          'stylesheets/app.css'
        ],
        dest: 'style.css'
      }
    },
    uglify: {
      site: {
        files: {
         'javascripts/site.<%= pkg.version %>.min.js': 'javascripts/site.js'
        }
      },
      foundation: {
        files: {
          'javascripts/foundation.min.js': 'javascripts/foundation.js'
        }
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
          'ally.<%= pkg.version %>.tar': [
            'style.css',
            '*.php',
            'fonts/*',
            'images/**',
            'javascripts/*min*.js'
          ]
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-contrib-jshint'); 
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  
  grunt.registerTask('default', ['jshint', 'concat', 'uglify']);
  grunt.registerTask('release', ['compress']);
};
