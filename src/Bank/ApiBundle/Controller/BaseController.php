<?php
/**
 * User: asmisha
 * Date: 15.11.14
 * Time: 11:42
 */

namespace Bank\ApiBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\Form;

class BaseController extends FOSRestController{
	/**
	 * @param null $data
	 * @param null $statusCode
	 * @param array $headers
	 * @return \FOS\RestBundle\View\View
	 */
	protected function view($data = null, $statusCode = null, array $headers = array()){
		$data = array(
			'response' => $data,
			'serverTime' => time()
		);

		return parent::view($data, $statusCode, $headers);
	}

	protected function getFormErrors(Form $form) {
		$errors = array();

		foreach ($form->getErrors() as $key => $error) {
			$errors[] = $error->getMessage();
		}

		foreach ($form->all() as $child) {
			if (!$child->isValid()) {
				$errors[$child->getName()] = $this->getFormErrors($child);
			}
		}

		return $errors;
	}
} 