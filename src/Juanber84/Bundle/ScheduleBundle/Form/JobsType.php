<?php

namespace Juanber84\Bundle\ScheduleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JobsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('initdatetime')
            ->add('enddatetime')
            ->add('observations')
            ->add('userid')
            ->add('proyectid')
            ->add('activityid')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Juanber84\Bundle\ScheduleBundle\Entity\Jobs'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'juanber84_bundle_schedulebundle_jobs';
    }
}
