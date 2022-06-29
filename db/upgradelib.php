<?php
/**
 * @package    block_ned_custom_menu
 * @subpackage NED
 * @copyright  2022 NED {@link http://ned.ca}
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \block_ned_custom_menu\shared_lib as NED;

/**
 * Function remove all blocks alt setting (now it's role)
 * Use only 1 time, because role settings can be lost
 */
function block_ned_custom_menu_remove_blocks_alt() {
    $alt_regex = '/(?:\n|^)(?:[^\|\n]*\|){2}([^\|\]\n]+)/';

    $parse_str_f = function($text) {
        $lines = explode("\n", $text);
        $require_update = false;
        foreach ($lines as &$line){
            $check_line = trim($line);
            if (empty($check_line)) continue;

            [$label, $url, $alt] = array_pad(explode('|', $check_line), 3, null);
            $alt = trim($alt);
            if (empty($alt)) continue;

            $require_update = true;
            $line = $label . '|' . $url;
        }

        $res = '';
        if ($require_update){
            $res = join("\n", $lines);
        }

        return [$require_update, $res];
    };

    $blocks = NED::db()->get_records('block_instances', ['blockname' => 'ned_custom_menu']);
    foreach ($blocks as $block){
        $inst = block_instance('ned_custom_menu', $block);
        if ($inst->config && $inst->config->text){
            $text = $inst->config->text;
            $res = [];
            preg_match($alt_regex, $text, $res);
            if(!empty($res)){
                $cfg = clone($inst->config);
                [$require_update, $txt] = $parse_str_f($text);
                if (!$require_update) continue;

                $cfg->text = $txt;
                $inst->instance_config_save($cfg);
            }
        }
    }
}
