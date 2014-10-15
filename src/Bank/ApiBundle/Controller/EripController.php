<?php

namespace Bank\ApiBundle\Controller;

use Bank\MainBundle\Entity\Account;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class EripController extends FOSRestController
{
	private	$availablePayments = array(
		1 => array(
			'name' => 'Electricity',
			'fields' => array('address' => 'Address'),
			'categoryId' => 1,
		),
		2 => array(
			'name' => 'Water',
			'fields' => array('address' => 'Address'),
			'categoryId' => 1,
		),
		3 => array(
			'name' => 'Byfly',
			'fields' => array('contractId' => 'Contract Id'),
			'categoryId' => 2,
		),
		4 => array(
			'name' => 'NIKS',
			'fields' => array('contractId' => 'Contract Id'),
			'categoryId' => 2,
		),
	);

	private function getTree(){
		$categories = array(
			1 => 'Household',
			2 => 'Internet',
		);

		$result = array();
		foreach($this->availablePayments as $id=>$p){
			if(!isset($result['categories'][$p['categoryId']])){
				$result['categories'][$p['categoryId']] = array(
					'name' => $categories[$p['categoryId']],
					'payments' => array(),
				);

				$p['paymentId'] = $id;
				$result['categories'][$p['categoryId']]['payments'][] = $p;
			}
		}

		return $result;
	}

	public function treeAction(){
		return $this->view($this->getTree());
	}

	public function payAction(Request $request){
		$paymentId = $request->get('paymentId');

		if(!isset($this->availablePayments[$paymentId])){
			throw new \Exception('Payment not found');
		}
		$payment = $this->availablePayments[$paymentId];

		$fields = array();
		$passedFields = $request->get('fields');
		foreach($payment['fields'] as $f=>$_){
			if(!isset($passedFields[$f])){
				throw new \Exception("Missing \"$f\" field");
			}
			$fields[$f] = $passedFields[$f];
		}

		$amount = $request->get('amount');

		/** @var Account $account */
		$account = $this->get('api')->getAccount();

		$message = sprintf(
			'Erip payment from user %d (%s %s) account id %d for %s (%s), amount %d: ',
			$account->getClient()->getId(),
			$account->getClient()->getFirstName(),
			$account->getClient()->getLastName(),
			$account->getId(),
			$this->availablePayments[$paymentId]['name'],
			json_encode($fields),
			$amount
		);

		try{
			$this->get('api')->writeOff($account, $amount);
		}catch(\Exception $e){
			$this->get('monolog.logger.erip')->info($message.'FAIL');

			throw $e;
		}

		$this->get('monolog.logger.erip')->info($message.'SUCCESS');

		return $this->view();
	}
}
