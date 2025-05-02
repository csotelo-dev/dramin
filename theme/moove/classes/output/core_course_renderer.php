<?php
namespace theme_moove\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/renderer.php');

class core_course_renderer extends \core_course_renderer {

    public function coursecat_coursebox($chelper, $course) {
        $content = html_writer::start_div('custom-course-card');

        $content .= html_writer::tag('h3', $course->get_formatted_name(), ['class' => 'course-title']);
        $content .= html_writer::tag('p', format_text($course->summary, FORMAT_HTML), ['class' => 'course-summary']);

        if ($imageurl = $this->get_course_image($course)) {
            $content .= html_writer::empty_tag('img', [
                'src' => $imageurl,
                'class' => 'course-image',
                'alt' => $course->get_formatted_name()
            ]);
        }

        $url = new \moodle_url('/course/view.php', ['id' => $course->id]);
        $content .= html_writer::link($url, 'Ir al curso →', ['class' => 'btn-enter-course']);

        $content .= html_writer::end_div();

        return $content;
    }

    // ESTA FUNCIÓN SÍ controla cómo se ven los cursos en la página principal
    public function frontpage_courses($courses, $totalcount) {
        global $CFG;

        if (!$totalcount) {
            return $this->output->heading(get_string('nocoursesyet'));
        }

        $output = html_writer::start_div('frontpage-course-list');
        $output .= html_writer::start_div('row');

        foreach ($courses as $course) {
            $url = new \moodle_url('/course/view.php', ['id' => $course->id]);

            $output .= html_writer::start_div('col-md-3');
            $output .= html_writer::start_div('custom-course-card');

            if ($imageurl = $this->get_course_image($course)) {
                $output .= html_writer::empty_tag('img', [
                    'src' => $imageurl,
                    'class' => 'course-image',
                    'alt' => $course->get_formatted_name()
                ]);
            }

            $output .= html_writer::tag('h3', $course->get_formatted_name(), ['class' => 'course-title']);
            $output .= html_writer::tag('p', format_text($course->summary, FORMAT_HTML), ['class' => 'course-summary']);
            $output .= html_writer::link($url, 'Ir al curso →', ['class' => 'btn-enter-course']);

            $output .= html_writer::end_div(); // .custom-course-card
            $output .= html_writer::end_div(); // .col-md-3
        }

        $output .= html_writer::end_div(); // .row
        $output .= html_writer::end_div(); // .frontpage-course-list

        return $output;
    }
}
