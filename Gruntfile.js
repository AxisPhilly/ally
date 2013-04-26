module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    jshint: {
      all: ['wp-content/themes/ally/javascripts/site.js']
    },
    concat: {
      options: {
        banner:  '/*\n' +
          ' Theme Name: Ally\n' +
          ' Theme URI: http://www.axisphilly.com\n' +
          ' Description: Custom theme for AxisPhilly\n' +
          ' Author: Casey Thomas, Jeff Frankl\n' +
          ' Author URI: axisphilly.org/about\n' +
          ' Version: <%= pkg.version %>\n' +
          '*/\n'
      },
      foundation: {
        src: [
          'wp-content/themes/ally/javascripts/foundation/jquery.cookie.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.event.move.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.event.swipe.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.accordion.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.alerts.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.buttons.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.clearing.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.forms.js',
          //'javascripts/foundation/jquery.foundation.joyride.js',
          //'javascripts/foundation/jquery.foundation.magellan.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.mediaQueryToggle.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.navigation.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.orbit.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.reveal.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.tabs.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.tooltips.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.foundation.topbar.js',
          //'javascripts/foundation/jquery.offcanvas.js',
          'wp-content/themes/ally/javascripts/foundation/jquery.placeholder.js',
          'wp-content/themes/ally/javascripts/foundation/app.js'
        ],
        dest: 'wp-content/themes/ally/javascripts/foundation.js'
      },
      libraries: {
        files: {
          'wp-content/themes/ally/javascripts/libraries.<%= pkg.version %>.min.js': [
            'wp-content/themes/ally/javascripts/contrib/underscore.min.js',
            'wp-content/themes/ally/javascripts/contrib/backbone.min.js',
            'wp-content/themes/ally/javascripts/contrib/bootstrap.affix.min.js',
            'wp-content/themes/ally/javascripts/contrib/fastclick.min.js'
          ],
          'wp-content/themes/ally/javascripts/libraries.latest.min.js': [
            'wp-content/themes/ally/javascripts/contrib/underscore.min.js',
            'wp-content/themes/ally/javascripts/contrib/backbone.min.js',
            'wp-content/themes/ally/javascripts/contrib/bootstrap.affix.min.js',
            'wp-content/themes/ally/javascripts/contrib/fastclick.min.js'
          ]
        }
      },
      sass: {
        src: [
          'wp-content/themes/ally/stylesheets/app.css'
        ],
        dest: 'wp-content/themes/ally/style.css'
      }
    },
    uglify: {
      site: {
        files: {
          'wp-content/themes/ally/javascripts/site.<%= pkg.version %>.min.js': 'wp-content/themes/ally/javascripts/site.js'
        }
      },
      site_latest: {
        files: {
           'wp-content/themes/ally/javascripts/site.latest.min.js': 'wp-content/themes/ally/javascripts/site.js'
        }
      },
      foundation: {
        files: {
          'wp-content/themes/ally/javascripts/foundation.min.js': 'wp-content/themes/ally/javascripts/foundation.js'
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
            'wp-content/themes/ally/style.css',
            'wp-content/themes/ally/*.php',
            'wp-content/themes/ally/fonts/*',
            'wp-content/themes/ally/images/**',
            'wp-content/themes/ally/javascripts/*min*.js'
          ]
        }
      }
    },
    s3: {
      key: process.env.AWS_ACCESS_KEY_ID,
      secret: process.env.AWS_SECRET_ACCESS_KEY,
      bucket: 'axisphilly-assets',
      access: 'public-read',
      upload: [
        {
          src: 'wp-content/themes/ally/javascripts/*min*.js',
          dest: 'javascripts/'
        },
        {
          src: 'wp-content/themes/ally/style.css',
          dest: 'stylesheets/style.css'
        },
        {
          src: 'wp-content/themes/ally/images/*',
          dest: 'images/'
        },
        {
          src: 'wp-content/themes/ally/fonts/*',
          dest: 'stylesheets/fonts/'
        }
      ]
    }
  });

  grunt.loadNpmTasks('grunt-contrib-compass');
  grunt.loadNpmTasks('grunt-contrib-compress');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-s3');

  grunt.registerTask('default', ['jshint', 'concat', 'uglify']);
  grunt.registerTask('style', ['concat:sass']);
  grunt.registerTask('release', ['compress']);
  grunt.registerTask('deploy-static', ['s3']);
};