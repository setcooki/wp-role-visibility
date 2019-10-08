module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON("package.json"),
        name: 'wp-role-visibility',
        compress: {
            dist: {
                options: {
                    mode: 'zip',
                    archive: 'dist/<%= name %>.zip'
                },
                files: [{
                    src: [
                        'src/**',
                        'lib/**',
                        'inc/**',
                        'config/**',
                        'static/**',
                        'locale/**',
                        'templates/**',
                        '<%= name %>.php',
                        'README.md',
                    ], dest: '<%= name %>/'
                }]
            }
        },
    });
    grunt.loadNpmTasks("grunt-contrib-compress");
    grunt.registerTask("dist", ["compress:dist"]);
};