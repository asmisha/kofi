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

		$this->localize($object);

		return $object;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getNewInstance()
	{
		$object = parent::getNewInstance();

		$this->localize($object);

		return $object;
	}

	private function localize($object){
		$this->getConfigurationPool()->getContainer()->get('localizator')->setLocales($object, array(
			'ErrorMessages',
			'Text',
		));
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
			->add('text', 'collection', array(
				'allow_add' => true,
			))
			->add('regex')
			->add('errorMessages', 'collection', array(
				'allow_add' => true,
			))
		;
	}
}