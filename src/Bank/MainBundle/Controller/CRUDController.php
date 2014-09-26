<?php

namespace Bank\MainBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;

class CRUDController extends Controller
{
	public function addAccountAction(){
		$id = $this->get('request')->get($this->admin->getIdParameter());

		$object = $this->admin->getObject($id);

		if (!$object) {
			throw $this->createNotFoundException(sprintf('unable to find the object with id : %s', $id));
		}

		$clonedObject = clone $object;  // Careful, you may need to overload the __clone method of your object
		// to set its id to null
		$clonedObject->setName($object->getName()." (Clone)");

		$this->admin->create($clonedObject);

		$this->addFlash('sonata_flash_success', 'Cloned successfully');

		return $this->redirect($this->admin->generateUrl('list'));
	}
}
