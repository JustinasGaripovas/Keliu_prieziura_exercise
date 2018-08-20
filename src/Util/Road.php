<?php
namespace App\Util;


class Road
{
    public $difference;
    public $begin;
    public $end;
    public $valid = true;

    function __construct($b, $e) {
        $this->begin = $b;
        $this->end = $e;
        $this->difference = $this->differenceCalc();
    }

    public function isEqual($obj)
    {
    return ($this->begin == $obj->begin && $this->end == $obj->end);
    }

    private function differenceCalc()
    {
        return abs(floatval($this->end) - floatval($this->begin));
    }

    public function __toString() {
        return $this->begin. ' ' . $this->end.' ->diff '.$this->difference;
    }


}