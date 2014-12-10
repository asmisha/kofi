<?php

namespace Bank\MainBundle\Admin;

use Bank\MainBundle\Entity\Account;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class AccountAdmin extends Admin{
	protected $formOptions = array(
		'cascade_validation' => true
	);

	public function getNewInstance(){
		/** @var Account $instance */
		$instance = parent::getNewInstance();

		if($clientId = $this->getRequest()->get('client_id')){
			$client = $this->getModelManager()->find('BankMainBundle:Client', $clientId);
			$instance->setClient($client);
		}

		return $instance;
	}

	protected function configureRoutes(RouteCollection $collection)
	{
		$collection
			->remove('delete')
			->remove('acl')
			->remove('export')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureListFields(ListMapper $listMapper)
	{
		$listMapper
			->addIdentifier('id', null, array('route' => array('name' => 'show')))
//			->add('client')
			->add('currency')
			->add('balance')
			->add('isActive', null, array('editable' => true))
			->add('createdAt')
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
			->add('client')
			->add('currency')
			->add('balance')
			->add('isActive')
			->add('createdAt')
			->add('cards')
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function configureFormFields(FormMapper $formMapper)
	{
		$isEdit = boolval($this->getSubject() && $this->getSubject()->getId());
		$isNested = $this->getParentFieldDescription() !== null;
//		var_dump(get_class($this->getParentFieldDescription()));
//		var_dump(get_class($this->getParentFieldDescription()));exit;

		$formMapper
			->add('id', null, array(
				'read_only' => true,
				'disabled' => true,
				'required' => false,
			))
		;

		if(!$isNested){
			$formMapper
				->add('client', null, array(
					'read_only' => $isEdit,
					'disabled'  => $isEdit,
				))
			;
		}

		$formMapper
			->add('currency', null, array(
				'read_only' => ($this->getSubject() && $this->getSubject()->getCurrency()),
				'disabled'  => ($this->getSubject() && $this->getSubject()->getCurrency()),
			))
			->add('balance', null, array(
				'read_only' => $isEdit,
				'disabled'  => $isEdit,
			))
			->add('isActive', null, array('required' => false))
			->add('cards', 'sonata_type_collection', array(
				'by_reference' => false,
				'btn_add' => $isNested ? false : 'link_add',
				'type_options' => array(
					// Prevents the "Delete" option from being displayed
					'delete' => false,
				),
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
			->add('client')
			->add('currency')
			->add('isActive')
			->add('createdAt')
		;
	}
}