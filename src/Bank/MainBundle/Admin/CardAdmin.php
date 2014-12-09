<?php

namespace Bank\MainBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class CardAdmin extends Admin{
	protected function configureRoutes(RouteCollection $collection)
	{
		$collection
			->remove('delete')
			->remove('acl')
			->remove('export')
			->remove('edit')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id', null, array('route' => array('name' => 'show')))
			->add('number')
			->add('isActive', null, array('editable' => true))
			->add('expiresAt', 'date', array('widget' => 'single_text'))
			// You may also specify the actions you want to be displayed in the list
			->add('_action', 'actions', array(
				'actions' => array(
					'show' => array(),
					'edit' => array(),
					'delete' => array(),
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
			->add('number')
			->add('cvv')
			->add('isActive')
			->add('expiresAt')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
//			->add('account')
			->add('number')
			->add('cvv')
			->add('expiresAt', 'date')
			->add('isActive', null, array('required' => false))
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureDatagridFilters(DatagridMapper $filterMapper)
	{
		$filterMapper
			->add('number')
			->add('expiresAt')
		;
	}
}