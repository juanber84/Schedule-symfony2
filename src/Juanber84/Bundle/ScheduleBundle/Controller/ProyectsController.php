<?php

namespace Juanber84\Bundle\ScheduleBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Juanber84\Bundle\ScheduleBundle\Entity\Proyects;
use Juanber84\Bundle\ScheduleBundle\Form\ProyectsType;

/**
 * Proyects controller.
 *
 * @Route("/schedule/proyects")
 */
class ProyectsController extends Controller
{

    /**
     * Lists all Proyects entities.
     *
     * @Route("/", name="schedule_proyects")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {

        $em    = $this->get('doctrine.orm.entity_manager');
        $dql   = "SELECT a FROM 'Juanber84ScheduleBundle:Proyects' a";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            3/*limit per page*/
        );        


        return array(
            'pagination' => $pagination
        );
    }
    /**
     * Creates a new Proyects entity.
     *
     * @Route("/", name="schedule_proyects_create")
     * @Method("POST")
     * @Template("Juanber84ScheduleBundle:Proyects:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Proyects();
        $form = $this->createForm(new ProyectsType(), $entity);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('schedule_proyects_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Proyects entity.
     *
     * @Route("/new", name="schedule_proyects_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Proyects();
        $form   = $this->createForm(new ProyectsType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Proyects entity.
     *
     * @Route("/{id}", name="schedule_proyects_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Juanber84ScheduleBundle:Proyects')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyects entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Proyects entity.
     *
     * @Route("/{id}/edit", name="schedule_proyects_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Juanber84ScheduleBundle:Proyects')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyects entity.');
        }

        $editForm = $this->createForm(new ProyectsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Proyects entity.
     *
     * @Route("/{id}", name="schedule_proyects_update")
     * @Method("PUT")
     * @Template("Juanber84ScheduleBundle:Proyects:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Juanber84ScheduleBundle:Proyects')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Proyects entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ProyectsType(), $entity);
        $editForm->submit($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('schedule_proyects_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Proyects entity.
     *
     * @Route("/{id}", name="schedule_proyects_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->submit($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Juanber84ScheduleBundle:Proyects')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Proyects entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('schedule_proyects'));
    }

    /**
     * Creates a form to delete a Proyects entity by id.
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
