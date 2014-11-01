<?php

namespace Bank\ApiBundle\Controller;

use Bank\MainBundle\Entity\Account;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class CurrencyController extends FOSRestController
{
	public function ratesAction(){
		$result = $this->getDoctrine()->getRepository('BankMainBundle:Currency')->createQueryBuilder('c')
			->select('c.id, c.code, c.name, c.rate')
			->getQuery()
			->getResult()
		;

		return $this->view($result);
	}
}
