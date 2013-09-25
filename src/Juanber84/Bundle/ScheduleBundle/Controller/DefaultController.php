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
        $totaltrabajado = '';
        $projects = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Project')->findAll();
        $activities = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Activity')->findAll();

        $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();

        $job = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Jobs')->findOneBy(array('enddatetime' => null, 'userid' => $profileId ));
        if (!$job) {
            $job = new Jobs();
            $inittime = '';
            $totaltrabajado = 0;
        } else {
            $inittime = $job->getInitdatetime();
            $inittime = $inittime->format('Y-m-d H:i:s');
            $nowtime = strtotime('now');
            $totaltrabajado = round(($nowtime-strtotime($inittime)));
        }

        if ($request->getMethod() == 'POST') {

            $punch = $request->request->get('punch');
            if ($punch != null) {
                $profileId  = $user = $this->get('security.context')->getToken()->getUser(); 
                $project = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Project')->findOneById($punch['project']);
                $activitie = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Activity')->findOneById($punch['activity']);
                if ($job->getEnddatetime() == null && $job->getInitdatetime() == null) {
                    $inittime = new \DateTime('now');
                    $job->setInitdatetime($inittime);
                }else{
                    if ($job->getEnddatetime() == null) {
                        $job->setEnddatetime(new \DateTime('now'));
                        $job->setObservations($punch['observations']);  
                        $inittime = '';
                    }else{
                        //$job->setInitdatetime(new \DateTime('now'));
                    }                
                }
                $job->setUserId($profileId);
                $job->setProjectid($project);
                $job->setActivityid($activitie);            
                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($job);
                $em->flush();


            }
            $job = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Jobs')->findOneBy(array('enddatetime' => null));            
        }

        return array(
            'job'           => $job,
            'projects'      => $projects,
            'activities'    => $activities,
            'inittime'      => $inittime,
            'totaltrabajado'=> $totaltrabajado,
        );
    }

}
