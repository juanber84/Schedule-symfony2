<?php

namespace Juanber84\Bundle\ScheduleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
}
