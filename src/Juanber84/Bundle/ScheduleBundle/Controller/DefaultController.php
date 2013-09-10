<?php

namespace Juanber84\Bundle\ScheduleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * @Route("/schedule/", name="schedule_jobs_index")
     * @Template()
     */
    public function scheduleAction(Request $request)
    {
        $proyects = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Proyects')->findAll();
        $activities = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Activity')->findAll();

        $job = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Jobs')->findOneBy(array('enddatetime' => null));
        if (!$job) {
            $job = new Jobs();
        }

        if ($request->getMethod() == 'POST') {

            $punch = $request->request->get('punch');
            $profileId  = $user = $this->get('security.context')->getToken()->getUser(); 
            $proyect = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Proyects')->findOneById($punch['proyect']);
            $activitie = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Activity')->findOneById($punch['activity']);
            $job->setUserId($profileId);
            $job->setProyectid($proyect);
            $job->setActivityid($activitie);
            $job->setInitdatetime(new \DateTime('now'));
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($job);
            $em->flush();
        }

        return array(
            'proyects'      => $proyects,
            'activities'    => $activities,
        );
    }

}
