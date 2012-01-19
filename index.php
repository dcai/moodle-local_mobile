<?php
/**
 * @package     local_mobile
 * @copyright   Dongsheng Cai {@link http://dongsheng.org}
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(dirname(dirname(dirname(__FILE__))) . '/config.php');
require(dirname(__FILE__) . '/lib.php');

require_login(SITEID, false);

$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('standard');
$PAGE->set_url('/local/mobile/index.php');
$PAGE->set_title(get_string('pluginname', 'local_mobile'));
$PAGE->set_heading(get_string('pluginname', 'local_mobile'));

$output = $PAGE->get_renderer('local_mobile');

echo $output->header();

echo $output->heading("Moodle for iPhone translation files");
$urlbase = "$CFG->httpswwwroot/pluginfile.php";
$fileurl = moodle_url::make_file_url($urlbase, "/" . SYSCONTEXTID . "/local_mobile/download/", true);

echo $output->box_start();
echo html_writer::link($fileurl, "Download");
echo $output->box_end();

echo $output->footer();
