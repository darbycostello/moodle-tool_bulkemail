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
 * Library functions for bulk email tool
 * 
 * @package    tool_bulkemail
 * @author     Darby Costello (darby@ghostvoid.com)
 * @copyright  2021 Ghostvoid Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Returns bulk user actions
 * 
 * @return array
 * @deprecated since Moodle 4.4. Please use the hook callback instead.
 * @todo MDL-80566 This will be deleted in Moodle 4.8
 */
function tool_bulkemail_bulk_user_actions() {
    global $CFG;
    debugging('Callback bulk_user_actions is deprecated. Please use hook callback instead.', DEBUG_DEVELOPER);
    return array(
        'bulkemail' => new action_link(
            new moodle_url($CFG->wwwroot.'/admin/tool/bulkemail/user_bulk_email.php'),
            get_string('bulkemail', 'tool_bulkemail'))
    );
}

/**
 * Hook callback registration
 */
function tool_bulkemail_extend_bulk_user_actions(): array {
    return [
        'core_user\hook\extend_bulk_user_actions' => [
            'tool_bulkemail\local\hooks\extend_bulk_user_actions_hook::callback',
        ],
    ];
}