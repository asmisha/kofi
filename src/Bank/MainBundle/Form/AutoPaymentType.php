<?php

namespace Bank\MainBundle\Form;

use Bank\ApiBundle\Form\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AutoPaymentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
				$builder
					->create('startDate', 'text', array(
						'invalid_message' => '"startDate" parameter should represent a valid timestamp.'
					))
					->addModelTransformer(new DateTimeToTimestampTransformer())
			)
            ->add('period', 'choice', array(
				'choices' => array(
					'day' => 'day',
					'week' => 'week',
					'month' => 'month',
					'year' => 'year',
				),
				'invalid_message' => '"period" parameter should be of values day, week, month or year',
			))
            ->add('data')
            ->add('type', 'choice', array(
				'choices' => array(
					'erip' => 'erip',
					'direct' => 'direct'
				),
				'invalid_message' => '"type" parameter should be of values erip or direct',
				'required' => true
			))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bank\MainBundle\Entity\AutoPayment',
			'csrf_protection' => false
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bank_mainbundle_autopayment';
    }
}
