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
        }else{
            $profileId  = $this->container->get('security.context')->getToken()->getUser()->getId();
            $dql   = "SELECT a FROM 'Juanber84ScheduleBundle:Jobs' a where a.userid = ".$profileId;
            $users = array('$profileId' => $this->container->get('security.context')->getToken()->getUser()->getUsername(), );
        }

        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );      

        $proyects = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Proyects')->findAll();
        $activities = $this->getDoctrine()->getRepository('Juanber84ScheduleBundle:Activity')->findAll();

        $form = $this->createFormBuilder()
            ->add('Proyect', 'choice', array(
                'choices'   => $proyects,
                'required'  => false,
            ))
            ->add('Activity', 'choice', array(
                'choices'   => $activities,
                'required'  => false,
            ))      
            ->add('User', 'choice', array(
                'choices'   => $users,
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
                // perform some action, such as saving the task to the database
                echo "string"; exit;
                return $this->redirect($this->generateUrl('task_success'));
            }
        }


        return array(
            'pagination' => $pagination,
            'form' => $form->createView(),
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
