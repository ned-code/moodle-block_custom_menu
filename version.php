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
 * @category   NED
 * @copyright  NED {@link http://ned.ca} 2017
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2024052300;        // The current plugin version (Date: YYYYMMDDXX)
$plugin->release = '2.9.0.1.4';
$plugin->requires = 2015051100.00;     // 2.9 (Build: 20150511).
$plugin->maturity = MATURITY_BETA;
$plugin->component = 'block_ned_custom_menu';      // Full name of the plugin (used for diagnostics)
$plugin->dependencies = array(
    'local_ned_controller' => 2024052300
);
