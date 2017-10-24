// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    block_ned_custom_menu
 * @subpackage NED
 * @copyright  NED {@link http://ned.ca} 2017
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @developer  G J Barnard - {@link http://about.me/gjbarnard} and
 *                           {@link http://moodle.org/user/profile.php?id=442195}
 * @originaldeveloper Michael Gardener <mgardener@cissq.com>
 */

/* jshint ignore:start */
define(['jquery', 'core/log'], function ($, log) {

    "use strict"; // jshint ;_;

    log.debug('Block NED Custom Menu jQuery AMD');

    return {
        init: function () {
            log.debug('Block NED Custom Menu AMD init initialised');

            $(document).ready(function () {
                var openimageurl = M.util.image_url('open', 'block_ned_custom_menu');
                log.debug('Block NED Custom Menu AMD init open image url - ' + openimageurl);
                $('#cssmenu ul li.has-sub.current div a').each(function(i, obj) {
                    if($(this).attr('title') == "no-highlight" ) {
                        var head_list = $(this).parent().closest('li').attr('class').split(' ');

                        $(this).css( "color", "#000" );
                        $('.' + head_list[0] + '.' + head_list[1] ).find('div').css({ "background": "none" });
                        $('.' + head_list[0] + '.' + head_list[1] ).find('.optionbullet').css({ "background": "url("+ openimageurl + ") 0 5px no-repeat" });
                        $('.' + head_list[0] + '.' + head_list[1] ).find('.optionbullet i').css({ "color": "#999" });
                    } else {
                        $(this).parent().prev().css({ 'color': '#fff' });
                    }
                });

                $('#cssmenu > ul > li.has-sub > div > div.optionbullet').click(function() {
                    var checkElement = $(this).parent().next();

                    $(this).parent().find(".optionbullet").removeClass("acc-menu-closed").addClass("acc-menu-open");

                    $('#cssmenu ul ul').each(function(i, obj) {
                        if($(this).is(':visible') && !$(this).hasClass('active')) {
                            $(this).slideUp('normal');
                            $(this).parent().find(".optionbullet").removeClass('acc-menu-open').addClass("acc-menu-closed");
                        }
                    });

                    if((checkElement.is('ul')) && (!checkElement.is(':visible'))) {
                        checkElement.slideDown('normal');
                    }
                });
            });
        }
    };
});
/* jshint ignore:end */
