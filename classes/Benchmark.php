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
 * @created    31/01/17 05:09
 * @package    local_kopere_dashboard
 * @copyright  2017 Eduardo Kraus {@link http://eduardokraus.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_kopere_dashboard;

defined('MOODLE_INTERNAL') || die();

use local_kopere_dashboard\html\Button;
use local_kopere_dashboard\report\ReportBenchmark;
use local_kopere_dashboard\report\ReportBenchmark_test;
use local_kopere_dashboard\util\DashboardUtil;
use local_kopere_dashboard\util\Mensagem;
use local_kopere_dashboard\util\TitleUtil;

class Benchmark {
    public function test() {
        DashboardUtil::startPage(get_string_kopere('benchmark_title'), null, null, 'Performace');

        echo '<div class="element-box">';
        Mensagem::printInfo(get_string_kopere('benchmark_based').'
                    <a class="alert-link" href="https://moodle.org/plugins/report_benchmark"
                       target="_blank">report_benchmark</a>');

        echo '<div style="text-align: center;">'.get_string_kopere('benchmark_info');
        Button::add(get_string_kopere('benchmark_execute'), 'Benchmark::execute');
        echo '</div>';

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function execute() {
        global $CFG;

        DashboardUtil::startPage(array(
            array('Benchmark::test', get_string_kopere('benchmark_title')),
            get_string_kopere('benchmark_executing')
        ));

        require_once($CFG->libdir.'/filelib.php');

        echo '<div class="element-box">';
        TitleUtil::printH3 ('benchmark_title2');

        $test = new ReportBenchmark();

        $score = $test->get_total();
        if ($score < 4) {
            Mensagem::printInfo('<strong>'.get_string_kopere('benchmark_timetotal').'</strong>    '.$this->formatNumber($score).' '.get_string_kopere('benchmark_seconds'));
        } else if ($score < 8) {
            Mensagem::printWarning('<strong>'.get_string_kopere('benchmark_timetotal').'</strong> '.$this->formatNumber($score).' '.get_string_kopere('benchmark_seconds'));
        } else {
            Mensagem::printDanger('<strong>'.get_string_kopere('benchmark_timetotal').'</strong>  '.$this->formatNumber($score).' '.get_string_kopere('benchmark_seconds'));
        }

        echo '<table class="table" id="benchmarkresult">
                  <thead>
                      <tr>
                          <th class="text-center media-middle">#</th>
                          <th>'.get_string_kopere('benchmark_decription').'</th>
                          <th class="media-middle">'.get_string_kopere('benchmark_timesec').'</th>
                          <th class="media-middle">'.get_string_kopere('benchmark_max').'</th>
                          <th class="media-middle">'.get_string_kopere('benchmark_critical').'</th>
                      </tr>
                  </thead>
                  <tbody>';

        foreach ($test->get_results() as $result) {
            echo "<tr class=\"{$result['class']}\" >
                      <td class=\"text-center media-middle\">{$result['id']}</td>
                      <td >{$result['name']}<div><small>{$result['info']}</small></div></td>
                      <td class=\"text-center media-middle\">".$this->formatNumber($result['during'])."</td>
                      <td class=\"text-center media-middle\">".$this->formatNumber($result['limit'])."</td>
                      <td class=\"text-center media-middle\">".$this->formatNumber($result['over'])."</td>
                  </tr>";
        }

        echo '</tbody></table>';

        TitleUtil::printH3('benchmark_testconf');
        $this->performance();

        echo '</div>';

        DashboardUtil::endPage();
    }

    public function performance() {
        global $CFG;

        echo "<table class=\"table\" id=\"benchmarkresult\">
                  <tr>
                      <th>".get_string_kopere('benchmark_testconf_problem')."</th>
                      <th>".get_string_kopere('benchmark_testconf_status')."</th>
                      <th>".get_string_kopere('benchmark_testconf_description')."</th>
                      <th>".get_string_kopere('benchmark_testconf_action')."</th>
                  </tr>";

        $tests = array(
            ReportBenchmark_test::themedesignermode(),
            ReportBenchmark_test::cachejs(),
            ReportBenchmark_test::debug(),
            ReportBenchmark_test::backup_auto_active(),
            ReportBenchmark_test::enablestats()
        );

        foreach ($tests as $test) {
            echo "<tr class='{$test['class']}'>
                      <td>{$test['title']}</td>
                      <td>{$test['resposta']}</td>
                      <td>{$test['description']}</td>
                      <td><a target=\"_blank\" href=\"{$CFG->wwwroot}/admin/{$test['url']}\">".get_string('edit', '')."</a></td>
                  </tr>";
        }

        echo '</tbody></table>';
    }

    private function formatNumber($number) {
        return str_replace('.', ',', $number);
    }
}