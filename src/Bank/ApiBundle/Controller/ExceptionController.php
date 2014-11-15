<?php

namespace Bank\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;

class ExceptionController extends BaseController
{
	public function handleAction($exception){
		return $this->view(array(
			'error' => array(
				'code' => $exception->getCode(),
				'message' => $exception->getMessage(),
			)
		));
	}
}
