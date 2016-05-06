'use strict';
module.exports = function(grunt) {
    var pkg = grunt.file.readJSON('package.json');

    grunt.initConfig({
        // setting folder templates
        dirs: {
            css: 'assets/css',
            js: 'assets/js',
            less: 'assets/less'
        },

        // Compile all .less files.
        less: {

            admin: {
                files: {
                    '<%= dirs.css %>/admin.css': '<%= dirs.less %>/admin/admin.less',
                    '<%= dirs.css %>/setup.css': '<%= dirs.less %>/admin/setup.less'
                }
            }
        },

        watch: {
            less: {
                files: ['<%= dirs.less %>/*.less', '<%= dirs.less %>/admin/*.less' ],
                tasks: ['less:admin'],
                options: {
                    livereload: true
                }
            }
        },

    });

    // Load NPM tasks to be used here
    grunt.loadNpmTasks( 'grunt-contrib-less' );
    grunt.loadNpmTasks( 'grunt-contrib-watch' );

    grunt.registerTask('default', ['less']);
};
