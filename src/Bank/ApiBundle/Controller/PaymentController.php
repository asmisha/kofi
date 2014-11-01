<?php

namespace Bank\ApiBundle\Controller;

use Bank\ApiBundle\Services\Api;
use Bank\MainBundle\Entity\Account;
use Bank\MainBundle\Entity\Card;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends FOSRestController
{
	public function payAction(Request $request){
		$fields = array('recipientBank', 'payerName', 'code', 'recipientAccountId', 'recipientName', 'amount');
		$data = array();
		foreach($fields as $f){
			if(!$request->get($f)){
				throw new \Exception("Missing \"$f\" field");
			}
			$data[$f] = $request->get($f);
		}

		/** @var Account $account */
		$account = $this->get('api')->getAccount();

		$message = sprintf(
			'General payment from user %d (%s %s) account id %d, data %s: ',
			$account->getClient()->getId(),
			$account->getClient()->getFirstName(),
			$account->getClient()->getLastName(),
			$account->getId(),
			json_encode($data)
		);

		$amount = $data['amount'];

		try{
			$this->get('api')->writeOff($account, $amount);
		}catch(\Exception $e){
			$this->get('monolog.logger.erip')->info($message.'FAIL');

			throw $e;
		}

		if($data['recipientBank'] == $this->get('service_container')->getParameter('bank_name')){
			try{
				$this->get('api')->charge($data['recipientAccountId'], $amount, $account->getCurrency());
			}catch(\Exception $e){
				$this->get('monolog.logger.erip')->info($message.'FAIL');
				$this->get('api')->charge($account, $amount, $account->getCurrency());

				throw $e;
			}
		}

		$this->get('monolog.logger.erip')->info($message.'SUCCESS');

		return $this->view();
	}
}
