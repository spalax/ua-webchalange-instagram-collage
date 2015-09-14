module.exports = function(grunt) {
    grunt.initConfig({
        cssmin: {
          options: {
            shorthandCompacting: false,
            roundingPrecision: -1
          },
          target: {
            files: {
              'public/css/frontend/site.css': ['public/css/frontend/common.css', 'public/vendor/bootstrap/dist/css/bootstrap.min.css', 'public/vendor/flexicolorpicker/themes.css','public/vendor/mjolnic-bootstrap-colorpicker/dist/css/bootstrap-colorpicker.css']
            }
          }
        },
        uglify: {
            my_target: {
                output: {
                    beautify: false
                },
                compress: {
                    sequences: true,
                    dead_code: true,
                    conditionals: true,
                    booleans: true,
                    unused: true,
                    if_return: true,
                    join_vars: true,
                    drop_console: true
                },
                files: {
                    'public/js/frontend/site.min.js':
                        ['public/vendor/jquery/dist/jquery.js',
                            'public/js/frontend/site.js',
                              'public/vendor/bootstrap/js/button.js',
                                'public/vendor/mjolnic-bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.registerTask('default', ['cssmin', 'uglify']);
};
