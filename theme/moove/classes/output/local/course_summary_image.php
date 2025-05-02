<?php

namespace theme_moove\output\local;

use renderable;
use templatable;
use stdClass;
use context_course;
use moodle_url;

class course_summary_image implements renderable, templatable {
    protected $courseid;

    public function __construct(int $courseid) {
        $this->courseid = $courseid;
    }

    public function export_for_template(\renderer_base $output): stdClass {
        $fs = get_file_storage();
        $contextid = context_course::instance($this->courseid)->id;

        $files = $fs->get_area_files($contextid, 'course', 'overviewfiles', 0, 'sortorder', false);
        $overviewfiles = [];

        foreach ($files as $file) {
            if ($file->is_directory()) {
                continue;
            }

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

        $data = new stdClass();
        $data->overviewfiles = $overviewfiles;
        return $data;
    }
}
