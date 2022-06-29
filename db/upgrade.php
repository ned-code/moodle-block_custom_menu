<?php

/**
 * @package    block_ned_custom_menu
 * @category   NED
 * @copyright  2022 NED {@link http://ned.ca}
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/upgradelib.php');

/**
 * @param int $oldversion
 *
 * @return bool
 * @noinspection PhpUnused
 */
function xmldb_block_ned_custom_menu_upgrade($oldversion) {
    global $DB, $CFG;
    $dbman = $DB->get_manager();

    if ($oldversion < 2022062900) {
        block_ned_custom_menu_remove_blocks_alt();
        upgrade_block_savepoint(true, 2022062900, 'ned_custom_menu');
    }

    return true;
}
