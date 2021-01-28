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

require_once('../../../config.php');
require_once($CFG->libdir.'/adminlib.php');
require_once($CFG->dirroot.'/admin/tool/bulkemail/user_email_form.php');

defined('MOODLE_INTERNAL') || die();

$message = optional_param('message', '', PARAM_CLEANHTML);
$subject = optional_param('subject', '', PARAM_TEXT);
$confirm = optional_param('confirm', 0, PARAM_BOOL);

require_login();
admin_externalpage_setup('userbulk');
require_capability('tool/bulkemail:sendbulkemails', context_system::instance());

$return = $CFG->wwwroot.'/admin/user/user_bulk.php';

if (empty($SESSION->bulk_users)) {
    redirect($return);
}

$supportuser = core_user::get_support_user();
if (empty($subject)) {
    $subject = get_string('email_from', 'tool_bulkemail', fullname($supportuser));
}

if ($confirm and !empty($message) and confirm_sesskey()) {
    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $users = $DB->get_recordset_select('user', "id $in", $params);
    if (empty($users)) {
        redirect($return);
    }
    foreach ($users as $user) {
        $recipient = null;
        $messagingpref = get_user_preferences('message_processor_email_with_subject_email', null, $user);
        $messagingpref = clean_param($messagingpref, PARAM_EMAIL);
        if (!empty($messagingpref) && !empty($CFG->messagingallowemailoverride)) {
            $recipient = clone($user);
            $recipient->email = $messagingpref;
        } else {
            $recipient = $user;
        }
        email_to_user($recipient, $supportuser, $subject, $message, $message);
    }
    $users->close();
    redirect($return);
}

$messageform = new user_email_form('user_bulk_email.php');

if ($messageform->is_cancelled()) {
    redirect($return);

} else if ($formdata = $messageform->get_data()) {
    $options = new stdClass();
    $options->para     = false;
    $options->newlines = true;
    $options->smiley   = false;

    $message = format_text($formdata->messagebody['text'], $formdata->messagebody['format'], $options);
    $subject = $formdata->subject;

    list($in, $params) = $DB->get_in_or_equal($SESSION->bulk_users);
    $userlist = $DB->get_records_select_menu('user', "id $in", $params, 'fullname', 'id,'.$DB->sql_fullname().' AS fullname');
    $usernames = implode(', ', $userlist);
    echo $OUTPUT->header();
    echo $OUTPUT->heading(get_string('confirmation', 'admin'));
    echo $OUTPUT->box('<h3>'.$subject.'</h3>', 'boxwidthnarrow boxaligncenter generalbox', 'preview');
    echo $OUTPUT->box($message, 'boxwidthnarrow boxaligncenter generalbox', 'preview');
    $formcontinue = new single_button(
        new moodle_url('user_bulk_email.php', array(
            'confirm' => 1, 'message' => $message, 'subject' => $subject)
        ), get_string('yes')
    );
    $formcancel = new single_button(new moodle_url($CFG->wwwroot.'/admin/user/user_bulk.php'), get_string('no'), 'get');
    echo $OUTPUT->confirm(get_string('confirmmessage', 'tool_bulkemail', $usernames), $formcontinue, $formcancel);
    echo $OUTPUT->footer();
    die;
}

echo $OUTPUT->header();
$messageform->display();
echo $OUTPUT->footer();
