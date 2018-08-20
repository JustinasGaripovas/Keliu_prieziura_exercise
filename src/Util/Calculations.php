<?php
/**
 * Created by PhpStorm.
 * User: jusgar
 * Date: 2018-08-12
 * Time: 17:42
 */

namespace App\Util;


class Calculations
{
    private $roads_done = array();
    public $roads_unmaintained = array();
    public $roads_maintained = array();
    private $roads = array();
    private $jobs = array();

    public function input($r_d, $r, $j)
    {
        $this->roads_done = $r_d;
        $this->roads = $r;

        $this->jobs = $j;
        $this->filterRoads();
    }

    public function jobFilter()
    {
        $result = array();
        $finalSections = $this->roadSelectionMerge();

        foreach ($this->jobs as $j) {
            foreach ($this->roads_done as $rD) {
                if ($j->job_id == $rD->job_id) {
                    foreach ($finalSections as $rS) {
                        if ($rD->road_section_begin == $rS->section_begin && $rD->road_section_end == $rS->section_end) {
                            array_push($result, $rD);
                        }
                    }
                }
            }
        }

        return $result;
    }

    public function filterRoads()
    {
        foreach ($this->roads_done as $current_object) {
            if ($current_object->timeMeasurement() == false) {
                array_push($this->roads_maintained, $current_object);
            }else{
                array_push($this->roads_unmaintained, $current_object);
            }
        }

        $this->roads_maintained = $this->maintainedRoads($this->roads_maintained);
    }

    public function getUnmaintainedRoads()
    {
        $result = array();
        $result2 = array();

        $roads = $this->roadSelectionMerge();

        foreach ($this->roads_maintained as $rm) {
            foreach ($roads as $x) {
                if (strcmp($rm->section_id, $x->section_id)==0) {
                    array_push($result, $rm->getUnmaintainedRoad($x->section_begin, $x->section_end));
                }
            }
        }

        foreach ($this->roads_unmaintained as $x) {
                array_push($result2, new MaintainedRoad($x->section_id, $x->road_section, $x->road_level, array($x->road_section_begin, $x->road_section_end)));
        }


        return array_unique(array_merge($result,$result2));
    }

    public function roadSelectionMerge()
    {
        $clone_roads = $this->roads;
        $result = array();
        $multipleFinal = array();

        for ($i = 0; $i < sizeof($this->roads) - 1; $i ++) {
            {
                $multiple = array();

                for ($e = $i; $e < sizeof($this->roads) - 1; $e ++) {
                    if ($this->roads[$i]->isEqualID($this->roads[$e]) && $this->roads[$i] !== $this->roads[$e] && $this->roads[$e]->valid && $this->roads[$i]->valid) {
                        array_push($multiple, $this->roads[$e]);
                        $this->roads[$e]->valid = false;
                    }
                }

                if (!empty($multiple)) {
                    array_push($multiple, $this->roads[$i]);
                    array_push($multipleFinal, $multiple);
                    $this->roads[$i]->valid = false;
                }
            }
        }

        foreach ($this->roads as $y)
        {
            $y->valid = true;
        }

        $obj = new RoadSelection();

        foreach ($multipleFinal as $mf)
        {
            $max = 0;
            $min = 99999;

            foreach ($mf as $m)
            {
                $obj = $m;
                if($m->section_begin < $min)
                {
                    $min = $m->section_begin;
                }

                if($m->section_end > $max)
                {
                    $max = $m->section_end;
                }
            }

            $obj->section_begin = $min;
            $obj->section_end = $max;

            array_push($result, $obj);
        }

        $obj->section_begin = $min;
        $obj->section_end = $max;

        $diff = array_udiff($clone_roads, $result,
            function ($obj_a, $obj_b) {
                return strcmp($obj_a->section_id, $obj_b->section_id);
            }
        );

        return array_merge($diff,$result);
    }

    public function maintainedRoads($maintained_roads)
    {
        $roads = array();
        foreach ($maintained_roads as $road_m)
        {
            if($road_m->valid == true) {
                $point_group = array();

                foreach ($maintained_roads as $road) {
                    if (strcmp($road_m->section_id, $road->section_id) == 0 && $road->valid == true) {
                        array_push($point_group, $road->road_section_begin);
                        array_push($point_group, $road->road_section_end);
                        $road->valid == false;
                    }
                }

                if (empty($point_group) == false) {
                    array_push($roads, new MaintainedRoad($road_m->section_id, $road_m->road_section, $road_m->road_level, $point_group));
                } else {
                    array_push($roads, new MaintainedRoad($road_m->section_id, $road_m->road_section, $road_m->road_level, $road_m->points));
                }

                $road_m->valid == false;
            }
        }

        return array_unique($roads);
    }
}