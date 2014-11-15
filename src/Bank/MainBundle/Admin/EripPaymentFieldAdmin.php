<?php

namespace Bank\MainBundle\Admin;

use Bank\MainBundle\Entity\Currency;
use Bank\MainBundle\Entity\EripPaymentField;
use Bank\MainBundle\Form\Type\GeneratedPasswordType;
use Bank\MainBundle\Form\Type\PasswordType;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class EripPaymentFieldAdmin extends Admin{
	/**
	 * {@inheritdoc}
	 */
	public function getObject($id)
	{
		$object = parent::getObject($id);

		return $this->setLocales($object);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNewInstance()
	{
		$object = parent::getNewInstance();

		return $this->setLocales($object);
	}

	/**
	 * @param EripPaymentField $object
	 * @return mixed
	 */
	private function setLocales($object){
		$locales = $this->getConfigurationPool()->getContainer()->getParameter('locales');
		$empty = array_combine(
			$locales,
			array_fill(0, count($locales), '')
		);

		@$object->setErrorMessages(is_array($object->getErrorMessages()) ? array_merge($empty, $object->getErrorMessages()) : $empty);

		return $object;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->add('name')
			->add('text')
			->add('regex')
			->add('errorMessages')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->add('name')
			->add('text')
			->add('regex')
			->add('errorMessages', 'collection', array(
				'allow_add' => true,
			))
		;
	}
}