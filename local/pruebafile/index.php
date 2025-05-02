<?php

require('../../config.php');
require_login();

$courseid = required_param('id', PARAM_INT);
$context = context_course::instance($courseid);
$fs = get_file_storage();
$files = $fs->get_area_files($context->id, 'course', 'overviewfiles', 0, 'sortorder', false);

echo "<h3>Archivos overviewfiles del curso $courseid</h3>";

foreach ($files as $file) {
    if ($file->is_directory()) {
        continue;
    }

    $contextid = $file->get_contextid();
    $filename = $file->get_filename();
    $encodedfilename = rawurlencode($filename);

    $url = $CFG->wwwroot . "/pluginfile.php/$contextid/course/overviewfiles/{$encodedfilename}";

    echo "<p><strong>$filename</strong></p>";
    echo "<img src=\"$url\" style=\"max-width:400px; border:1px solid #000\" /><hr>";
}
