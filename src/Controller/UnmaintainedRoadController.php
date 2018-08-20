<?php
namespace App\Controller;
use App\Util\Calculations;
use App\Util\done_job;
use App\Util\Inputs;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class UnmaintainedRoadController extends AbstractController
{

    /**
     * @Route("/kelias")
     */
    public function home()
    {
        $info = new Inputs();
        $calc = new Calculations();

        $roads_done = $info->inputRoadsDoneSelection();
        $jobs = $info->inputInfoJobs();
        $roads = $info->inputInfoRoadSelection();

        $calc->input($roads_done,$roads,$jobs);

        $result = $calc->getUnmaintainedRoads();


        $max = 0;
        foreach ($result as $r)
        {
            if(sizeof($r->points) > $max)
            {
                $max = sizeof($r->points);
            }
        }

        return $this->render('UnmaintainedRoad/show.html.twig',[
            'data' => $result,
            'tableLength' => $max,
        ]);
    }

    /**
     * @Route("/filtras")
     */
    public function filter()
    {
        $calc = new Calculations();
        $info = new Inputs();

        $roads_done = $info->inputRoadsDoneSelection();
        $jobs = $info->inputInfoJobs();
        $roads = $info->inputInfoRoadSelection();


        $calc->input($roads_done,$roads,$jobs);

        $job_filter = $calc->jobFilter();
        return $this->render('FilterRoadsForJobs/show.html.twig',[
            'data' => $job_filter,
        ]);
    }
}