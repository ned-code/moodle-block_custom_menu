<?php

/**
 * Settings for the ned_custom_menu block.
 *
 * @package    block_ned_custom_menu
 * @subpackage NED
 * @copyright  NED {@link http://ned.ca} 2017
 * @author     NED {@link http://ned.ca}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
use \block_ned_custom_menu\shared_lib as NED;

/**
 * Class block_ned_custom_menu_edit_form
 *
 * @noinspection PhpUnused
 */
class block_ned_custom_menu_edit_form extends block_edit_form {
    /**
     * @param MoodleQuickForm $mform
     */
    protected function specific_definition($mform) {
        global $CFG;

        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        $mform->addElement('static', 'blockinfo', NED::str('blockinfo'),
            '<a target="_blank" href="//ned.ca/custom-menu">ned.ca/custom-menu</a>');

        $mform->addElement('text', 'config_title', NED::str('configtitle'));
        $mform->setType('config_title', PARAM_TEXT);

        // Menu options.
        $params = array(
            'style' => 'width: 100%;min-height: 200px;'
        );
        $mform->addElement('textarea', 'config_text', NED::str('configcontent'), $params);
        $mform->setType('config_text', PARAM_TEXT);
        $this->_add_description_element(\local_ned_controller\output\custom_ned_menu\custom_ned_menu::get_menu_format_description());

        if (!empty($CFG->block_ned_custom_menu_allowcssclasses)) {
            $mform->addElement('text', 'config_classes', get_string('configclasses', 'block_ned_custom_menu'));
            $mform->setType('config_classes', PARAM_TEXT);
            $mform->addHelpButton('config_classes', 'configclasses', 'block_ned_custom_menu');
        }
    }

    /**
     * Add static element, than positioned under fields (not under fields labels),
     *  which looks like field description
     *
     * @param string $text
     *
     * @return void
     */
    protected function _add_description_element($text){
        static $_i = 0;
        $el = &$this->_form->addElement('static', 'description_element_'.($_i++), '',
            NED::div($text, 'd-flex'));
        $el->_label = NED::HTML_SPACE; // make margin through label
    }

    /**
     * Load in existing data as form defaults. Usually new entry defaults are stored directly in
     * form definition (new entry form); this function is used to load in data where values
     * already exist and data is being edited (edit entry form).
     *
     * note: $slashed param removed
     *
     * @param \stdClass|array $defaults object or array of default values
     */
    public function set_data($defaults) {
        // Backward compatibility.
        // Convert html content to plain text content.
        if (!empty($this->block->config->text)) {
            $defaults->config_text = $this->block->config->text;
        }
        parent::set_data($defaults);
    }
}
