<?php

namespace Bank\MainBundle\Admin;

use Bank\MainBundle\Entity\Currency;
use Bank\MainBundle\Form\Type\GeneratedPasswordType;
use Bank\MainBundle\Form\Type\PasswordType;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class CurrencyAdmin extends Admin{
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
	 * @param Currency $object
	 */
	private function localize($object){
		$this->getConfigurationPool()->getContainer()->get('localizator')->setLocales($object, array(
			'NameLocalized',
		));
	}


	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id', null, array('route' => array('name' => 'show')))
			->add('name')
			->add('code')
			->add('rate')
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
	protected function configureShowFields(ShowMapper $showMapper)
	{
		$showMapper
			->add('id')
			->add('name')
			->add('nameLocalized')
			->add('code')
			->add('rate')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
			->add('name')
			->add('nameLocalized', 'collection', array(
//				'allow_add' => true,
			))
			->add('code')
			->add('rate')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureDatagridFilters(DatagridMapper $filterMapper)
	{
		$filterMapper
			->add('name')
			->add('code')
		;
	}
}