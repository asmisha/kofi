<?php

namespace Bank\MainBundle\Admin;

use Bank\MainBundle\Entity\Currency;
use Bank\MainBundle\Entity\EripPayment;
use Bank\MainBundle\Form\Type\GeneratedPasswordType;
use Bank\MainBundle\Form\Type\PasswordType;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class EripPaymentAdmin extends Admin{
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

	/**
	 * @param EripPayment $object
	 */
	private function localize($object){
		$this->getConfigurationPool()->getContainer()->get('localizator')->setLocales($object, array(
			'Name',
		));

		foreach($object->getFields() as $f){
			$this->getConfigurationPool()->getContainer()->get('localizator')->setLocales($f, array(
				'Text',
				'ErrorMessages',
			));
		}
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id')
			->add('category', null, array('editable' => true))
			->add('name', 'array')
			// You may also specify the actions you want to be displayed in the list
			->add('_action', 'actions', array(
				'actions' => array(
					'edit' => array(),
				)
			))
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->add('name', 'collection', array(
				'allow_add' => true,
			))
			->add('category')
			->add('fields', 'sonata_type_collection', array(
				'by_reference' => false
			), array(
				'edit' => 'inline',
				'inline' => 'table',
				'sortable' => 'position',
			))
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureDatagridFilters(DatagridMapper $filterMapper)
	{
		$filterMapper
			->add('category')
			->add('name')
		;
	}
}