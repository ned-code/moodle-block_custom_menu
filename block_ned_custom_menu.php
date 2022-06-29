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
 * Settings for the ned_custom_menu block.
 * Including modifications to simplify the block for using as a simple
 * menu, with simple textual options (no embedded files).
 *
 * @package    block_ned_custom_menu
 * @subpackage NED
 * @copyright  NED {@link http://ned.ca} 2017
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \block_ned_custom_menu\shared_lib as NED;
use \local_ned_controller\output\custom_ned_menu\custom_ned_menu;

/**
 * Class block_ned_custom_menu
 */
class block_ned_custom_menu extends block_base implements \renderable {

    /**
     * @var custom_ned_menu
     */
    protected $_menu;

    public function init() {
        $this->title = NED::str('pluginname');
    }

    /**
     * {@inheritDoc}
     */
    public function has_config() {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function applicable_formats() {
        return array('all' => true);
    }

    /**
     * {@inheritDoc}
     */
    public function specialization() {
        $base_title = format_string(empty($this->config->title) ? NED::str('pluginname') : $this->config->title);

        if (NED::during_update()){
            $this->title = $base_title;
            return;
        }

        $format_title = $this->get_menu()->get_header_menu_item()->get_format_title();
        if (isset($format_title[0])){
            $title = NED::span(
                [
                    NED::div($base_title, 'raw-title'),
                    $format_title,
                ],
                'dynamic-title'
            );
        } else {
            $title = $base_title;
        }

        $this->title = $title;
    }

    /**
     * {@inheritDoc}
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /**
     * {@inheritDoc}
     */
    public function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();
        if (!empty($CFG->block_ned_custom_menu_allowcssclasses)){
            if (!empty($this->config->classes)){
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        $attributes = array_merge($attributes, $this->get_menu()->get_block_html_attributes());
        $attributes['class'] = $attributes['class'] ?? '';
        $attributes['class'] .= ' '.NED::$PLUGIN_NAME;

        return $attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function get_content() {
        if (!is_null($this->content)) return $this->content;
        if (empty($this->config->text)) return null;

        $this->content = new stdClass;
        $this->content->text = ' '; // space, not empty
        $this->content->footer = '';

        // Generate the html.
        $menu_item = $this->get_menu()->get_header_menu_item();
        if (!$menu_item->is_empty()){
            $this->content->text = NED::render($menu_item);
        }

        return $this->content;
    }

    /**
     * @return custom_ned_menu
     */
    public function get_menu(){
        if (empty($this->_menu)){
            $text = $this->config->text;
            $header_menu_on = false;
            if (method_exists('format_ned', 'get_active_format_ned')){
                $format = format_ned::get_active_format_ned($this->page->course->id);
                if ($format){
                    $header_menu_on = $format->is_setting_header_block();
                }
            }
            $this->_menu = NED::new_custom_menu_item($text, $header_menu_on, $this->page->course->id);
        }

        return $this->_menu;
    }
}
