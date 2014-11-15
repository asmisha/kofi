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
	 * @param EripPayment $object
	 * @return mixed
	 */
	private function setLocales($object){
		$locales = $this->getConfigurationPool()->getContainer()->getParameter('locales');

		foreach($object->getFields() as $f){
			$empty = array_combine(
				$locales,
				array_fill(0, count($locales), '')
			);

			@$f->setErrorMessages(is_array($f->getErrorMessages()) ? array_merge($empty, $f->getErrorMessages()) : $empty);
		}

		return $object;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id')
			->add('category', null, array('editable' => true))
			->add('name', null, array('editable' => true))
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
			->add('name')
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