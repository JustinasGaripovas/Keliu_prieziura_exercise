<?php
/**
 * Created by PhpStorm.
 * User: jusgar
 * Date: 2018-08-12
 * Time: 18:53
 */

namespace App\Util;


class MaintainedRoad
{
    public $section_id = '';
    public $section_name = '';
    public $level = '';
    public $points = array();

    function __construct($s_id, $s_na, $l, $points_array) {
        $this->section_id = $s_id;
        $this->section_name = $s_na;
        $this->level = $l;
        $this->points = $points_array;
    }

    public function getUnmaintainedRoad($road_begin, $road_end)
    {
        $gaps = array();

        $range_array = $this->overlap();

        if(sizeof($range_array)>1) {
            $gapsIndex = 0;

            if ($road_begin != $range_array[0]->begin) {
                if($road_begin < $range_array[0]->begin)
                {
                    array_push($gaps, $road_begin);
                    array_push($gaps, $range_array[0]->begin);
                    $gapsIndex++;
                }else{
                    array_push($gaps, $range_array[0]->begin);
                    $gapsIndex++;
                }
            }

            array_push($gaps, $range_array[0]->end);
            $gapsIndex++;

            for ($i=1;$i<sizeof($range_array); $i++) {
                array_push($gaps, $range_array[$i]->begin);
                $gapsIndex++;

                array_push($gaps, $range_array[$i]->end);
                $gapsIndex++;
            }

            array_push($gaps, $road_end);

        }else{
            $gaps=$this->points;
        }

        return new MaintainedRoad($this->section_id,$this->section_name,$this->level,$gaps);
    }

    public function overlap()
    {
        $temp_road = array();

            for ($i = 0; $i < sizeof($this->points) - 1; $i += 2) {
                array_push($temp_road, new Road($this->points[$i], $this->points[$i + 1]));
            }

        $temp_road = array_unique($temp_road);
        if(sizeof($temp_road) > 1) {

            $temp_road = $this->sortArrayWithObjects($temp_road, 'difference');


            $temp_road = $this->overlapping($temp_road);
            if(sizeof($temp_road) > 1) {
                $temp_road = $this->overlapping($temp_road);
                $temp_road = $this->overlapping($temp_road);
            }

            $temp_road = $this->sortArrayWithObjects($temp_road, 'begin');
            $temp_road = array_reverse($temp_road);

            return array_unique($temp_road);

        }else{
            return $temp_road;
        }
    }

    private function overlapping($temp_road)
    {
        $gaps = array();
        $gaps = $this->sortArrayWithObjects($gaps, 'begin');

        $min = $temp_road[0]->begin;
        $max = $temp_road[0]->end;

        if (sizeof($temp_road) > 1) {

            for ($i = 1; $i <= sizeof($temp_road) - 1; $i++) {

                foreach ($gaps as $gap) {
                    if ($gap->begin == $temp_road[$i]->begin || $gap->end == $temp_road[$i]->end) {
                        continue;
                    }
                }

                if ($this->isInside($min, $max, $temp_road[$i]->begin) && $this->isInside($min, $max, $temp_road[$i]->end)) {
                    $temp_road[$i]->valid = false;
                    continue;
                }

                if ($this->isInside($min, $max, $temp_road[$i]->begin) == false && $this->isInside($min, $max, $temp_road[$i]->end) == false) {
                    if ($temp_road[$i]->begin < $min && $temp_road[$i]->end < $min) {
                        array_push($gaps, $temp_road[$i]);
                        continue;
                    }

                    if ($temp_road[$i]->begin > $max && $temp_road[$i]->end > $max) {
                        array_push($gaps, $temp_road[$i]);
                        continue;
                    }

                    if ($temp_road[$i]->begin < $min && $temp_road[$i]->end > $max) {
                        $min = $temp_road[$i]->begin;
                        $max = $temp_road[$i]->end;
                        continue;
                    }
                }

                if ($this->isInside($min, $max, $temp_road[$i]->begin) == true && $this->isInside($min, $max, $temp_road[$i]->end) == false) {
                    $max = $temp_road[$i]->end;
                    continue;
                }

                if ($this->isInside($min, $max, $temp_road[$i]->begin) == false && $this->isInside($min, $max, $temp_road[$i]->end) == true) {
                    $min = $temp_road[$i]->begin;
                    continue;
                }
            }

            array_push($gaps, new Road($min, $max));
        }

        return $gaps;
    }

    function sortArrayWithObjects($array, $property)
    {
        usort($array, function ($a, $b) use ($property) {
            $a = floatval($a->$property);
            $b = floatval($b->$property);
            return (($a === $b) ? 0 : (($a > $b) ? -1 : 1));
        });
        return $array;
    }

    function isNotNull($val)
    {
        return !is_null($val);
    }

    private function isInside($min, $max, $value)
    {
        return ($value >= $min && $value <= $max);
    }

    public function __toString()
    {
        $string = $this->section_id. ' | ' . $this->section_name.' | '.$this->level . ' | ';

        foreach ($this->points as $p)
        {
            $string .= $p . ", ";
        }

        return $string . "<br>";
    }
}
