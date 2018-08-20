<?php
/**
 * Created by PhpStorm.
 * User: jusgar
 * Date: 2018-08-12
 * Time: 17:29
 */

namespace App\Util;


class RoadSelection
{
    public $id = '';
    public $section_id = '';
    public $section_name = '';
    public $unit_id = '';
    public $section_begin = '';
    public $section_end = '';
    public $level = '';
    public $valid = true;


    public function inputInformation($i, $s_id, $s_na, $u_id, $s_b, $s_e, $l)
    {
        $this->id = $i;
        $this->section_id = $s_id;
        $this->section_name = $s_na;
        $this->unit_id = $u_id;
        $this->section_begin = $s_b;
        $this->section_end = $s_e;
        $this->level = $l;
    }

    public function returnLength()
    {
        return $this->section_end - $this->section_begin;
    }

    public function isEqual($obj)
    {
        return ($this->section_begin == $obj->section_begin && $this->section_end == $obj->section_end);
    }

    public function isEqualID($obj)
    {
        return ($this->section_id == $obj->section_id);
    }

    public function compareBegin($value)
    {
        return (($this->section_begin === $value) ? 0 : ($this->section_begin > $value) ? 1 : -1);
    }

    public function compareEnd($value)
    {
        return (($this->section_end === $value) ? 0 : $this->section_end < $value ? 1 : -1);
    }

    public function __toString()
    {
        return " id-> " . $this->section_id . " section_name-> " . $this->section_name . " unit_id-> " . $this->unit_id . " level-> " . $this->level . "<br>";
    }
}