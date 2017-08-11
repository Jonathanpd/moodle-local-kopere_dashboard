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
 * @created    10/06/17 23:13
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard\html\inputs;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\TinyMce;

class InputHtmlEditor extends InputTextarea {
    /**
     * @return InputHtmlEditor
     */
    public static function newInstance() {
        return new InputHtmlEditor();
    }

    /**
     * @return string
     */
    public function toString() {
        $this->setStyle($this->getStyle() . ';height:500px');

        $returnInput = parent::toString();
        $returnInput .= TinyMce::createInputEditor('#' . $this->getName());

        return $returnInput;
    }
}