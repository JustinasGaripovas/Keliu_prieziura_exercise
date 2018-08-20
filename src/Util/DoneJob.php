<?php
/**
 * Created by PhpStorm.
 * User: jusgar
 * Date: 2018-08-12
 * Time: 13:30
 */

namespace App\Util;


class DoneJob
{
    public $id = '2';
    public $job_id = '';
    public $job_name = '';
    public $road_section = '';
    public $road_section_begin = '';
    public $road_section_end = '';
    public $unit_of = '';
    public $quantity = '';
    public $done_job_date = '';
    public $note = '';
    public $section_id = '';
    public $road_level = '';
    public $valid = true;


    public function inputInformation($i, $j_id, $j_na, $ro, $ro_b, $ro_e, $u_o, $q, $d_j_d, $n, $s_i, $r_l)
    {
        $this->id = $i;
        $this->job_id = $j_id;
        $this->job_name = $j_na;
        $this->road_section = $ro;
        $this->road_section_begin = $ro_b;
        $this->road_section_end = $ro_e;
        $this->unit_of = $u_o;
        $this->quantity = $q;
        $this->done_job_date = $d_j_d;
        $this->note = $n;
        $this->section_id = $s_i;
        $this->road_level = $r_l;
    }

    public function timeMeasurement()
    {
        $d = new \DateTime();
        $d = strtotime($this->done_job_date);
        $d = date('Y-m-d H:i', $d);

        $dNow = new \DateTime();
        $dNow = date('Y-m-d H:i', strtotime("-30 day", strtotime($dNow->format('Y-m-d H:i'))));

        return $d < $dNow;
    }

    public function __toString()
    {
        return $this->road_section_begin. '|' . $this->road_section_end.'|'.$this->section_id . ' ' . $this->job_id . ' ' . $this->job_name. ' ' . $this->road_section_begin. ' ' . $this->road_section_end;
    }
}