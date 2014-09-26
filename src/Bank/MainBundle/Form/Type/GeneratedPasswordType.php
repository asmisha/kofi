<?php
/**
 * User: asmisha
 * Date: 26.09.14
 * Time: 11:36
 */

namespace Bank\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GeneratedPasswordType extends AbstractType
{
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'required' => false,
		));
	}

	public function getParent()
	{
		return 'text';
	}

	public function getName()
	{
		return 'generated_password';
	}
}