/**
 * Gruntfile for compiling block_ned_custom_menu AMD files.
 *
 * This file configures tasks to be run by Grunt
 * http://gruntjs.com/ for the current format.
 *
 *
 * Requirements:
 * -------------
 * nodejs, npm, grunt-cli.
 *
 * Installation:
 * -------------
 * node and npm: instructions at http://nodejs.org/
 *
 * grunt-cli: `[sudo] npm install -g grunt-cli`
 *
 * node dependencies: run `npm install` in the root directory.
 *
 *
 * Usage:
 * ------
 * Call tasks from the theme root directory. Default behaviour
 * (calling only `grunt`) is to run the watch task detailed below.
 *
 *
 * Porcelain tasks:
 * ----------------
 * The nice user interface intended for everyday use. Provide a
 * high level of automation and convenience for specific use-cases.
 *
 * grunt amd     Create the Asynchronous Module Definition JavaScript files.  See: MDL-49046.
 *               Done here as core Gruntfile.js currently *nix only.
 *
 * Plumbing tasks & targets:
 * -------------------------
 * Lower level tasks encapsulating a specific piece of functionality
 * but usually only useful when called in combination with another.
 *
 * grunt replace             Run all text replace tasks.
 *
 * @package    block_ned_custom_menu
 * @subpackage NED
 * @copyright  NED {@link http://ned.ca}
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @developer  G J Barnard - {@link http://about.me/gjbarnard} and
 *                           {@link http://moodle.org/user/profile.php?id=442195}
 */

module.exports = function(grunt) { // jshint ignore:line

    // Import modules.
    var path = require('path');

    // PHP strings for exec task.
    var moodleroot = path.dirname(path.dirname(__dirname)), // jshint ignore:line
        configfile = '',
        decachephp = '',
        dirrootopt = grunt.option('dirroot') || process.env.MOODLE_DIR || ''; // jshint ignore:line

    // Allow user to explicitly define Moodle root dir.
    if ('' !== dirrootopt) {
        moodleroot = path.resolve(dirrootopt);
    }

    var PWD = process.cwd(); // jshint ignore:line
    configfile = path.join(moodleroot, 'config.php');

    decachephp += 'define(\'CLI_SCRIPT\', true);';
    decachephp += 'require(\'' + configfile + '\');';
    decachephp += 'theme_reset_all_caches();';

    grunt.initConfig({
        exec: {
            decache: {
                cmd: 'php -r "' + decachephp + '"',
                callback: function(error) {
                    // exec will output error messages just add one to confirm success.
                    if (!error) {
                        grunt.log.writeln("Moodle cache reset.");
                    }
                }
            }
        },
        jshint: {
            options: {jshintrc: '.jshintrc'},
            files: ['**/amd/src/*.js']
        },
        uglify: {
            options: {
                preserveComments: 'some'
            },
            dynamic_mappings: {
                files: grunt.file.expandMapping(
                    ['**/src/*.js', '!**/node_modules/**'],
                    '',
                    {
                        cwd: PWD,
                        rename: function(destBase, destPath) {
                            destPath = destPath.replace('src', 'build');
                            destPath = destPath.replace('.js', '.min.js');
                            destPath = path.resolve(PWD, destPath);
                            return destPath;
                        }
                    }
                )
            }
        }
    });

    // Load contrib tasks.
    grunt.loadNpmTasks("grunt-exec");

    // Load core tasks.
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-jshint');

    // Register tasks.
    grunt.registerTask("default", ["watch"]);
    grunt.registerTask("decache", ["exec:decache"]);

    grunt.registerTask("amd", ["jshint", "uglify", "decache"]);
};
