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
 * Hook callback for bulk email actions
 * 
 * @package    tool_bulkemail
 * @copyright  2024 Ghostvoid Ltd
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_bulkemail\local\hooks;

use core_user\hook\extend_bulk_user_actions;

/**
 * Hook callback implementation for bulk email actions
 */
class extend_bulk_user_actions_hook {
    
    /**
     * Add bulk email action to the actions list
     *
     * @param extend_bulk_user_actions $hook
     */
    public static function callback(extend_bulk_user_actions $hook): void {
        global $CFG;
        require_once($CFG->dirroot . '/admin/tool/bulkemail/lib.php');
        
        $actions = tool_bulkemail_bulk_user_actions();
        foreach ($actions as $actionid => $action) {
            $hook->add_action($actionid, $action);
        }
    }
}