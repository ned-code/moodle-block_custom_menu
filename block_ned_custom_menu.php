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
 * @developer  G J Barnard - {@link http://about.me/gjbarnard} and
 *                           {@link http://moodle.org/user/profile.php?id=442195}
 * @originaldevelopers Michael Gardener <mgardener@cissq.com> & Itamar Tzadok <itamar@substantialmethods.com>
 */

class block_ned_custom_menu extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_ned_custom_menu');
    }

    function has_config() {
        return true;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        $this->title = isset($this->config->title) ? format_string($this->config->title) : format_string(get_string('pluginname', 'block_ned_custom_menu'));
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_required_javascript() {
        parent::get_required_javascript();

        $this->page->requires->js_call_amd('block_ned_custom_menu/block_ned_custom_menu_main', 'init', array());
        //$this->page->requires->jquery();
        //$this->page->requires->js('/blocks/ned_custom_menu/js/main.js');
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

    /*
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!empty($CFG->block_ned_custom_menu_allowcssclasses)) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        return $attributes;
    }

    function get_content() {
        global $CFG, $COURSE;

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (empty($this->config->text)) {
            return null;
        }

        // Backward compatibility.
        // Convert html content to plain text content.
        $text = $this->config->text;
        $this->config->text = $this->convert_options_html_to_text($text);

        // Generate array of menu options.
        $options = array_map('trim', explode("\n", $this->config->text));
        $parent = -1;
        $menuoptions = array();
        foreach ($options as $key => $option) {
            list($label, $url, $alt) = array_pad(explode('|', $option), 3, null);

            // Check if submenu.
            $submenu = false;
            if (strpos($label, '-') === 0) {
                $submenu = true;
                $label = trim(substr($label, 1));
            }

            if ($submenu) {
                if ($parent < 0) {
                    // The menu options seem to start with sub option.
                    continue;
                }
                // CHEN ADDED ALTS
                $menuoptions[$parent]['submenu'][$label] = array(
                    'label' => $label,
                    'url' => $url,
                    'alt' => $alt,
                    'inner' => 1,
                    'submenu' => array()
                );
                // CHEN ADDED ALTS
            } else {
                $parent = $key;
                $menuoptions[$parent] = array(
                    'label' => $label,
                    'url' => $url,
                    'alt' => $alt,
                    'inner' => 0,
                    'submenu' => array()
                );
            }
        }

        // Generate the html.
        $menuhtml = $this->get_menu_html($menuoptions);

        $wrapperdiv = \html_writer::tag('div', $menuhtml, array('id' => 'nedcmenu'));

        $this->content = new stdClass;
        $this->content->text = $wrapperdiv;
        $this->content->footer = '';
        return $this->content;
    }

    /**
     * Backward compatibility method to convert old html representation of the menu options
     * in existing instances to the new simplified representation.
     * The method replaces the content of config->text if required.
     *
     * @return void
     */
    public function convert_options_html_to_text($str) {
        // No conversion if no text.
        if (empty($str)) {
            return '';
        }

        // No conversion if text is not html.
        if($str == strip_tags($str)) {
            return $str;
        }

        $text = '';

        // Text is the previous html version so convert to the new plain text representation.
        $dom = new DOMDocument();
        $dom->loadHTML($str);
        // Get the first ul.
        $uls = $dom->getElementsByTagName('ul');
        if ($uls->length) {
            $ul = $uls->item(0);
            $text = $this->get_options_text($ul);
        }

        return $text;
    }

    /**
     * Backward compatibility method to convert old html representation of the menu options
     * in existing instances to the new simplified representation.
     * The method replaces the content of config->text if required.
     *
     * @param DOMNode $ul
     * @param int $level Sub menu level.
     * @return string
     */
    protected function get_options_text($ul, $level = 0) {
        $text = '';

        // Make sure there are child nodes.
        if (!$ul or !$ul->hasChildNodes()) {
            return $text;
        }

        // Iterate the ul recursively and collate the options.
        foreach ($ul->childNodes as $cn) {
            // Node must be li.
            if (strtolower($cn->nodeName) != 'li') {
                continue;
            }

            // Get the first a tag.
            $as = $cn->getElementsByTagName('a');
            if (!$as->length) {
                continue;
            }
            $a = $as->item(0);
            $optionlabel = $a->nodeValue;
            $optionurl = $a->getAttribute('href');
            $pref = str_repeat('-', $level);

            // Add its value and url to to text.
            $text .= "$pref$optionlabel|$optionurl\n";

            // Look for sub options.
            $subuls = $cn->getElementsByTagName('ul');
            if ($subuls->length) {
                // There should be only one anyway.
                $subul = $subuls->item(0);
                $text .= $this->get_options_text($subul, $level + 1);
            }
        }
        return $text;
    }

    protected function get_menu_html(array $menuoptions, &$current = false) {
        global $CFG, $OUTPUT;

        $menuhtml = '';

        foreach ($menuoptions as $menuoption) {
            list($label, $url, $alt, $inner, $submenuoptions) = array_values($menuoption);

            if (!$label or !$url) {
                continue;
            }

            $liclasses = array();

            // Get the full url for section anchors.
            if (strpos($url, '#section-') === 0) {
                $sectionnum = str_replace('#section-', '', $url);
                $urlparams = array(
                    'id' => $this->page->course->id,
                    'section' => $sectionnum,
                );
                $fullurl = new moodle_url("$CFG->wwwroot/course/view.php", $urlparams);
            } else {
                $fullurl = new moodle_url($url);
            }
            // Edit label for font-awesome (CHEN).
            $str = $label;

            $parsme = explode("[fa-", $str);
            for ($i=1; $i < count($parsme); $i++) {
                $tempstring = '';
                $findAwesome = explode("]", $parsme[$i]);
                for ($j=1; $j < count($findAwesome); $j++) {
                    $tempstring .= $findAwesome[$j]."]";
                }

                $tempstring = mb_substr($tempstring, 0, -1);

                $parsme[$i] = $tempstring;
            }

            $label2 = "";
            foreach ($parsme as $k) {
                $label2 .= $k;
            }
            // End font awesome filter.

            $link = \html_writer::link($fullurl, $label2, array('title' => $alt));

            // Check if option is active.
            if ($this->page->url->out(false) == $fullurl->out(false)) {
                $liclasses[] = 'current';
                $current = true;
            }

            $submenuhtml = '';
            if ($submenuoptions) {
                $liclasses[] = 'has-sub';
                $submenuhtml = $this->get_menu_html($submenuoptions, $current);
            } else {
                $liclasses[] = 'hasnot-sub';
                if (!$inner) {
                    $current = false;
                }
            }

            if ($inner) {
                $parsme2 = explode("[fa-", $str);
                for ($i=1; $i < count($parsme2); $i++) {
                    $findAwesome2 = explode("]", $parsme2[$i]);
                    $tempstring = "<i class=\"fa fa-".$findAwesome2[0]."\"></i>";
                    for ($j=1; $j < count($findAwesome2); $j++) {
                        $tempstring .= $findAwesome2[$j]."]";
                    }

                    $tempstring = mb_substr($tempstring, 0, -1);

                    $parsme2[$i] = $tempstring;
                }

                $label2 = "";
                foreach ($parsme2 as $k) {
                    $label2 .= $k;
                }
                // End font awesome filter.

                $link = \html_writer::link($fullurl, $label2, array('title'=>$alt));

                $optionslabel = $link;
            } else {
                if( $parsme[0] ) {
                    $bulletclass = $current ? 'optionbullet acc-menu-open' : 'optionbullet';
                    $bullet = \html_writer::tag('div', null, array('class' => $bulletclass));
                } else {
                    $parsme1 = explode("[fa-", $str);
                    $tempstring1 = '';
                    for ($i=1; $i < count($parsme1); $i++) {
                       $findAwesome1 = explode("]", $parsme1[$i]);
                       $tempstring1 = "<i class=\"fa fa-".$findAwesome1[0]."\"></i>";
                    }

                    $bulletclass = $current ? 'optionbullet acc-menu-open hideBulletOption' : 'optionbullet hideBulletOption';
                    $bullet = \html_writer::tag('div', $tempstring1, array('class' => $bulletclass));
                }
                $current = false;

                $link = \html_writer::tag('div', $link);
                $optionslabel = \html_writer::tag('div', $bullet. $link);
            }

            $li = \html_writer::tag('li', $optionslabel. $submenuhtml, array('class' => implode(' ', $liclasses)));
            $menuhtml .= $li;
        }

        $ulactive = $current ? array('class' => 'active') : null;

        return \html_writer::tag('ul', $menuhtml, $ulactive);
    }
}
