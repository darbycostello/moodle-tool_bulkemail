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
 * @package tool
 * @subpackage bulkemail
 * @author Darby Costello (darby@ghostvoid.com)
 * @copyright 2021 Ghostvoid Ltd
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');

class user_email_form extends moodleform {
    public function definition() {
        $mform =& $this->_form;
        $mform->addElement('header', 'general', 'Email');
        $mform->addElement('text', 'subject', 'Subject');
        $mform->setType('subject', PARAM_TEXT);
        $mform->addRule('subject', '', 'required', null, 'server');
        $mform->addElement('editor', 'messagebody', get_string('messagebody'), null, null);
        $mform->addRule('messagebody', '', 'required', null, 'server');
        $this->add_action_buttons();
    }
}
