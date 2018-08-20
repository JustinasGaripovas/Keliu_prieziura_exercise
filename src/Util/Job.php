<?php
/**
 * Created by PhpStorm.
 * User: jusgar
 * Date: 2018-08-12
 * Time: 17:54
 */

namespace App\Util;


class Job
{
    public $id = '';
    public $job_id = '';
    public $job_name = '';
    public $job_quantity = '';

    public function inputInformation($i, $j_id, $j_na, $j_q)
    {
        $this->id = $i;
        $this->job_id = $j_id;
        $this->job_name = $j_na;
        $this->job_quantity = $j_q;
    }

    public function output()
    {
        return $this->id . ' ' . $this->job_id . ' ' . $this->job_name;
    }
}