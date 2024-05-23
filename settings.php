<?php
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

defined('MOODLE_INTERNAL') || die;

global $ADMIN;
if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox('block_ned_custom_menu_allowcssclasses',
        get_string('allowadditionalcssclasses', 'block_ned_custom_menu'),
        get_string('configallowadditionalcssclasses', 'block_ned_custom_menu'), 0));
}


