<?php

namespace Juanber84\Bundle\ScheduleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Juanber84\Bundle\ScheduleBundle\Entity\Jobs;
use Juanber84\Bundle\ScheduleBundle\Form\JobsType;

/**
 * Jobs controller.
 *
 * @Route("/schedule/jobs")
 */
class JobsController extends Controller
{

    /**
     * Lists all Jobs entities.
     *
     * @Route("/", name="schedule_jobs")
     * @Template()
     */
    public function indexAction(Request $request)
    {

        $em    = $this->get('doctrine.orm.entity_manager');

        if (true === $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $dql   = "SELECT a FROM 'Juanber84ScheduleBundle:Jobs' a";
            $users = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:User')->findAll();
            $arrayusers= array();
            foreach ($users as $value) {
                $arrayusers[$value->getId()] = $value->getUsername();
            }                
        }else{
            $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();
            $dql   = "SELECT a FROM 'Juanber84ScheduleBundle:Jobs' a where a.userid = ".$profileId;
            $arrayusers = array('$profileId' => $this->container->get('security.context')->getToken()->getUser()->getUsername(), );
        }   

        $projects = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Project')->findAll();
        $arrayproyects = array();
        foreach ($projects as $value) {
            $arrayproyects[$value->getId()] = $value->getName();
        }
        $activities = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Activity')->findAll();
        $arrayactivities = array();
        foreach ($activities as $value) {
            $arrayactivities[$value->getId()] = $value->getName();
        }
        $form = $this->createFormBuilder()
            ->add('Project', 'choice', array(
                'choices'   => $arrayproyects,
                'required'  => false,
            ))
            ->add('Activity', 'choice', array(
                'choices'   => $arrayactivities,
                'required'  => false,
            ))      
            ->add('User', 'choice', array(
                'choices'   => $arrayusers,
                'required'  => false,
            ))                 
            ->add('Init', 'text', array(
                'required'  => false,
            ))
            ->add('End', 'text', array(
                'required'  => false,
            ))            
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $formrequest = $request->request->get('form');
                $param1 = '';
                if ($formrequest['Project'] != '') {
                    $param1 = ' and a.projectid ='.$formrequest['Project'];
                }
                $param2 = '';
                if ($formrequest['Activity'] != '') {
                    $param2 = ' and a.activityid ='.$formrequest['Activity'];
                }           
                $param3 = '';
                if ($formrequest['User'] != '') {
                    $param3 = ' and a.userid ='.$formrequest['User'];
                }  
                $param4 = '';
                if ($formrequest['Init'] != '') {
                    $param4 = " and a.initdatetime > '".$formrequest["Init"]." 00:00:00'";
                }       
                $param5 = '';   
                if ($formrequest['End'] != '') {
                    $param5 = " and a.enddatetime < '".$formrequest["End"]." 23:59:59'";
                }    
                if (true === $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
                    $dql   = "SELECT a FROM 'Juanber84ScheduleBundle:Jobs' a where 1 = 1".$param1.$param2.$param3.$param4.$param5;
                    $users = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:User')->findAll();
                }else{
                    $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();
                    $dql   = "SELECT a FROM 'Juanber84ScheduleBundle:Jobs' a where a.userid = ".$profileId.$param1.$param2.$param3.$param4.$param5;
                    $users = array('$profileId' => $this->container->get('security.context')->getToken()->getUser()->getUsername(), );
                }
            }
        }

        $query = $em->createQuery($dql);
        $registers = $query->getResult();
        $numberregisters = count($query);
        $numberhours = 0;
        $raised = 0;
        foreach ($registers as $key => $value) {
            if ($value->getEnddatetime() != null ) {
                $timed = (strtotime($value->getEnddatetime()->format('Y-m-d H:i:s'))-strtotime($value->getInitdatetime()->format('Y-m-d H:i:s')))/60/60;
                if ($value->getProjectid()->getPrice() != '') {
                    $raised = $raised + ($value->getProjectid()->getPrice()*$timed);# code...
                }
                $numberhours = $numberhours + (strtotime($value->getEnddatetime()->format('Y-m-d H:i:s'))-strtotime($value->getInitdatetime()->format('Y-m-d H:i:s')));
            }
        }
        $hours = (int)($numberhours/60/60);
        $numberhours = $numberhours - ($hours*60*60);
        $minutes = (int)($numberhours/60);
        $seconds = $numberhours - ($minutes*60);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );   

        return array(
            'pagination'        => $pagination,
            'form'              => $form->createView(),
            'numberregisters'   => $numberregisters,
            'hours'             => $hours,
            'minutes'           => $minutes,
            'seconds'           => $seconds,
            'raised'            => $raised,
        );
    }

    /**
     * Creates a new Jobs entity.
     *
     * @Route("/create", name="schedule_jobs_create")
     * @Method("POST")
     * @Template("Juanber84ScheduleBundle:Jobs:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Jobs();
        $form = $this->createForm(new JobsType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('schedule_jobs_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Jobs entity.
     *
     * @Route("/new", name="schedule_jobs_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Jobs();
        $form   = $this->createForm(new JobsType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Jobs entity.
     *
     * @Route("/{id}", name="schedule_jobs_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        if (true === $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->find($id);
        }else{
            $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();
            $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->findOneBy(array('id' => $id, 'userid' => $profileId ));            
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jobs entity or you dont have permisions.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Jobs entity.
     *
     * @Route("/{id}/edit", name="schedule_jobs_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        if (true === $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->find($id);
        }else{
            $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();
            $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->findOneBy(array('id' => $id, 'userid' => $profileId ));            
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jobs entity or you dont have permisions.');
        }

        $editForm = $this->createForm(new JobsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Jobs entity.
     *
     * @Route("/{id}", name="schedule_jobs_update")
     * @Method("PUT")
     * @Template("Juanber84ScheduleBundle:Jobs:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        if (true === $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->find($id);
        }else{
            $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();
            $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->findOneBy(array('id' => $id, 'userid' => $profileId ));            
        }

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Jobs entity or you dont have permisions.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new JobsType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('schedule_jobs_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Jobs entity.
     *
     * @Route("/{id}", name="schedule_jobs_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            if (true === $this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
                $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->find($id);
            }else{
                $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();
                $entity = $em->getRepository('Juanber84ScheduleBundle:Jobs')->findOneBy(array('id' => $id, 'userid' => $profileId ));            
            }

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Jobs entity or you dont have permisions.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('schedule_jobs'));
    }

    /**
     * Creates a form to delete a Jobs entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
