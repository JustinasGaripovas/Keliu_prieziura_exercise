<?php
/**
 * Created by PhpStorm.
 * User: jusgar
 * Date: 2018-08-12
 * Time: 17:42
 */

namespace App\Util;


class Inputs
{
    private $directory = '\Resources';

    public function inputRoadsDoneSelection()
    {
        $files = array_slice(scandir(__DIR__ .$this->directory), 2);

        $roads = array();

        $handle = fopen(__DIR__ .$this->directory . '\\' . $files[0], "r");
        if ($handle) {
            $line = fgets($handle);
            while (($line = fgets($handle)) !== false) {
                $current_object = new DoneJob();
                $data = explode(";", $line);

                $current_object->inputInformation($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6],$data[7],$data[8],$data[9],$data[10],$data[11]);
                array_push($roads, $current_object);
            }
            fclose($handle);
        } else {
            var_dump(fopen);
        }

        return $roads;
    }

    public function inputInfoRoadSelection()
    {
        $files = array_slice(scandir(__DIR__ .$this->directory), 2);

        $roads = array();

        $handle = fopen(__DIR__ .$this->directory . '\\' . $files[2], "r");
        if ($handle) {
            $line = fgets($handle);
            while (($line = fgets($handle)) !== false) {
                $current_object = new RoadSelection();
                $data = explode(";", $line);

                $current_object->inputInformation($data[0],$data[1],$data[2],$data[3],$data[4],$data[5],$data[6]);
                array_push($roads, $current_object);
            }
            fclose($handle);
        } else {
            var_dump(fopen);
        }

        return $roads;
    }

    public function inputInfoJobs()
    {
        $files = array_slice(scandir(__DIR__ .$this->directory), 2);

        $jobs = array();

        $handle = fopen(__DIR__ .$this->directory . '\\' . $files[1], "r");
        if ($handle) {
            $line = fgets($handle);
            while (($line = fgets($handle)) !== false) {
                $current_object = new Job();
                $data = explode(";", $line);

                $current_object->inputInformation($data[0],$data[1],$data[2],$data[3]);
                array_push($jobs, $current_object);
            }
            fclose($handle);
        } else {
            var_dump(fopen);
        }

        return $jobs;
    }
}