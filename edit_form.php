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
 * Original editor element for adding menu options replaced with simple text area.
 * The expected menu options syntax is:
 * Option|url
 * -Sub option|url
 *
 * @package    block_ned_custom_menu
 * @subpackage NED
 * @copyright  NED {@link http://ned.ca} 2017
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @developer  G J Barnard - {@link http://about.me/gjbarnard} and
 *                           {@link http://moodle.org/user/profile.php?id=442195}
 * @originaldevelopers Michael Gardener <mgardener@cissq.com> & Itamar Tzadok <itamar@substantialmethods.com>
 */

class block_ned_custom_menu_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        global $CFG;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('static', 'blockinfo', get_string('blockinfo', 'block_ned_custom_menu'),
            '<a target="_blank" href="//ned.ca/custom-menu">ned.ca/custom-menu</a>');

        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_ned_custom_menu'));
        $mform->setType('config_title', PARAM_TEXT);

        // Menu options.
        $params = array(
            'style' => 'width: 100%;min-height: 200px;'
        );
        $mform->addElement('textarea', 'config_text', get_string('configcontent', 'block_ned_custom_menu'), $params);
        $mform->setType('config_text', PARAM_TEXT);

        if (!empty($CFG->block_ned_custom_menu_allowcssclasses)) {
            $mform->addElement('text', 'config_classes', get_string('configclasses', 'block_ned_custom_menu'));
            $mform->setType('config_classes', PARAM_TEXT);
            $mform->addHelpButton('config_classes', 'configclasses', 'block_ned_custom_menu');
        }
    }

    public function set_data($defaults) {
        // Backward compatibility.
        // Convert html content to plain text content.
        if (!empty($this->block->config->text)) {
            $text = $this->block->config->text;
            $defaults->config_text = $this->block->convert_options_html_to_text($text);
        }
        parent::set_data($defaults);
    }

}
