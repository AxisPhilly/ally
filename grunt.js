module.exports = function(grunt) {
  grunt.initConfig({
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
          'javascripts/foundation/jquery.foundation.modernizr.foundation.js',
          'javascripts/foundation/app.js'
        ],
        dest: 'theme/javascripts/foundation.js'
      },
      libraries: {
        src: 'javascripts/contrib/*',
        dest: 'theme/javascripts/libraries.min.js'
      },
      sass: {
        src: [
          'stylesheets/app.css',
          'stylesheets/function-overrides.css',
          'stylesheets/compass.css'
        ],
        dest: 'theme/style.css'
      }
    },
    min: {
      site: {
        src: 'javascripts/site.js',
        dest: 'theme/javascripts/site.min.js'
      },
      foundation: {
        src: 'theme/javascripts/foundation.js',
        dest: 'theme/javascripts/foundation.min.js'
      }
    }
    /*compass: {
      dist: {
        options: {
          sassDir: 'sass',
          cssDir: 'theme'
        }
      }
    }*/
  });

  grunt.loadNpmTasks('grunt-contrib-compass');

  grunt.registerTask('default', 'concat lint min');
};