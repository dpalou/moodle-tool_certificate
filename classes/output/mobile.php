<?php
// This file is part of the tool_certificate plugin for Moodle - http://moodle.org/
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

namespace tool_certificate\output;

/**
 * Mobile output class for tool_certificate module.
 *
 * @package     tool_certificate
 * @copyright   2025 Dani Palou <dani@moodle.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mobile {

    /**
     * Returns the my certificates view for the mobile app.
     *
     * @param  array $args Arguments from tool_mobile_get_content WS
     * @return array       HTML, javascript and otherdata
     */
    public static function mobile_my_certificates_view(array $args): array {
        global $OUTPUT, $CFG;

        $args = (object) $args;

        $user = \core_user::get_user($args->userid ?: $USER->id, '*', MUST_EXIST);
        if (!\tool_certificate\permission::can_view_list($user->id)) {
            throw new \required_capability_exception(context_system::instance(), 'tool/certificate:viewallcertificates',
                'nopermission', 'error');
        }

        // @todo: Obtain list of certificates. You can see the fields needed in the mobile_my_certificates_page template.
        // Make sure to apply \core_external\util::format_string to name and coursename.
        $certificates = [];

        $data = [
            'certificates' => $certificates,
            'hascertificates' => !empty($certificates),
            'canverify' => \tool_certificate\permission::can_verify(),
            // @todo: Calculate showshareonlinkedin using the config, like it's done in get_shareonlinkedincerturl.
            'showshareonlinkedin' => 0,
        ];

        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => $OUTPUT->render_from_template('tool_certificate/mobile_my_certificates_page', $data),
                ],
            ],
            'javascript' => '',
            'otherdata' => '',
            'files' => '',
        ];
    }

    /**
     * Returns the info and javascript needed to initialize my certificates in the app.
     *
     * @param  array $args Arguments from tool_mobile_get_content WS
     * @return array       HTML, javascript and otherdata
     */
    public static function mobile_my_certificates_init(array $args): array {
        return [
            'templates' => [],
            'javascript' => '',
            'restrict' => [
                'courses' => [SITEID],
            ],
        ];
    }

}
