<?php
/**
 * @package    block_ned_custom_menu
 * @category   NED
 * @copyright  2022 NED {@link http://ned.ca}
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_ned_custom_menu;

defined('MOODLE_INTERNAL') || die();

/**
 * Class shared_lib
 *
 * @package block_ned_custom_menu
 */
class shared_lib extends \local_ned_controller\shared\base_class {
    use \local_ned_controller\shared\base_trait;
}

shared_lib::init();
