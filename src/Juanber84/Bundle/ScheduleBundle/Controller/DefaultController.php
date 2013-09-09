<?php

namespace Juanber84\Bundle\ScheduleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Juanber84\Bundle\ScheduleBundle\Entity\Jobs;
use Juanber84\Bundle\ScheduleBundle\Form\JobsType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="schedule_jobs_indexweb")
     * @Template()
     */
    public function indexAction($name = 'Juan')
    {
        return array('name' => $name);
    }

    /**
     * Lists all Jobs entities.
     *
     * @Route("/schedule", name="schedule_jobs_index")
     * @Template()
     */
    public function scheduleAction(Request $request)
    {

        return array(

        );
    }

}
