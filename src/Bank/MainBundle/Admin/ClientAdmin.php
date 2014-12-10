<?php

namespace Bank\MainBundle\Admin;

use Bank\MainBundle\Form\Type\GeneratedPasswordType;
use Bank\MainBundle\Form\Type\PasswordType;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class ClientAdmin extends Admin{
	protected $formOptions = array(
		'cascade_validation' => true
	);

	protected function configureRoutes(RouteCollection $collection)
	{
		$collection
			->add('addAccount', $this->getRouterIdParameter().'/addAccount')
			->remove('delete')
			->remove('acl')
			->remove('export')
		;
	}
	public function getFormTheme(){
		return array_merge(parent::getFormTheme(), array(
			'BankMainBundle:Form:generated_password.html.twig'
		));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id', null, array('route' => array('name' => 'show')))
			->add('firstName')
			->add('middleName')
			->add('lastName')
			->add('passportSeries')
			->add('passportNumber')
			->add('passportIssueDate')
			->add('passportIssueAuthority')
			->add('createdAt')
			->add('isActive', null, array('editable' => true))
			// You may also specify the actions you want to be displayed in the list
			->add('_action', 'actions', array(
				'actions' => array(
					'show' => array(),
					'edit' => array(),
					'delete' => array(),
					'add account' => array(
						'template' => 'BankMainBundle:CRUD:list__action_addAccount.html.twig'
					)
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
			->add('firstName')
			->add('middleName')
			->add('lastName')
			->add('passportSeries')
			->add('passportNumber')
			->add('passportIssueDate')
			->add('passportIssueAuthority')
			->add('isActive')
			->add('createdAt')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureFormFields(FormMapper $formMapper)
	{
		$isEdit = boolval($this->getSubject() && $this->getSubject()->getId());

		$formMapper
			->add('firstName', null, array(
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('middleName', null, array(
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('lastName', null, array(
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('passportSeries', null, array(
				'error_bubbling' => false,
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('passportNumber', null, array(
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('passportIssueDate', 'date', array(
				'widget' => 'single_text',
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('passportIssueAuthority', null, array(
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('accounts', 'sonata_type_collection', array(
				'by_reference' => false,
				'type_options' => array(
					// Prevents the "Delete" option from being displayed
					'delete' => false,
				),
			), array(
				'edit' => 'inline',
				'inline' => 'table',
				'sortable' => 'position',
			))
			->add('isActive', null, array('required' => false))
			->add('plainPassword', new GeneratedPasswordType())
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureDatagridFilters(DatagridMapper $filterMapper)
	{
		$filterMapper
			->add('firstName')
			->add('middleName')
			->add('lastName')
			->add('passportSeries')
			->add('passportNumber')
			->add('createdAt')
		;
	}
}