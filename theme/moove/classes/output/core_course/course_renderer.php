<?php
namespace theme_moove\output\core_course;

use core_course\output\course_page;
use context_course;
use moodle_url;

class core_renderer extends \core_course\output\core_renderer {

    public function render_course_page(course_page $page): string {
        global $CFG;

        $context = $page->export_for_template($this);
        $course = $page->get_course();
        $contextid = context_course::instance($course->id)->id;
        $fs = get_file_storage();
        $files = $fs->get_area_files($contextid, 'course', 'overviewfiles', 0, 'sortorder', false);

        $overviewfiles = [];

        foreach ($files as $file) {
            if ($file->is_directory()) continue;

            $overviewfiles[] = [
                'fileurl' => moodle_url::make_pluginfile_url(
                    $file->get_contextid(),
                    $file->get_component(),
                    $file->get_filearea(),
                    $file->get_itemid(),
                    $file->get_filepath(),
                    $file->get_filename()
                )->out(false),
                'name' => $file->get_filename(),
                'image' => strpos($file->get_mimetype(), 'image/') === 0
            ];
        }

        $context['course']['overviewfiles'] = $overviewfiles;
        $context['course']['summary'] = format_text($course->summary, $course->summaryformat);

        return $this->render_from_template('core_course/course', $context);
    }
}
