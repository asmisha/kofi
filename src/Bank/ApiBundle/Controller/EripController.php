<?php

namespace Bank\ApiBundle\Controller;

use Bank\MainBundle\Entity\Account;
use Bank\MainBundle\Entity\EripCategory;
use Bank\MainBundle\Entity\EripPayment;
use Bank\MainBundle\Entity\Operation;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class EripController extends BaseController
{
	public function treeAction(){
		$categories = $this->getDoctrine()->getRepository('BankMainBundle:EripCategory')->createQueryBuilder('ec')
			->select('ec, ep, epf')
			->join('ec.payments', 'ep')
			->leftJoin('ep.fields', 'epf')
			->getQuery()
			->getResult();

		$data = array();
		foreach($categories as $c){
			/** @var EripCategory $c */

			$payments = array();

			foreach($c->getPayments() as $p){
				$fields = array();

				foreach($p->getFields() as $f){
					$fields[$f->getName()] = array(
						'name' => $f->getText(),
						'regex' => $f->getRegex(),
						'errorMessage' => $f->getErrorMessages()
					);
				}

				$payments[] = array(
					'paymentId' => $p->getId(),
					'name' => $p->getName(),
					'fields' => $fields
				);
			}

			$data['categories'][$c->getId()] = array(
				'name' => $c->getName(),
				'payments' => $payments
			);
		}

		return $this->view($data);
	}

	public function payAction(Request $request){
		$paymentId = $request->get('paymentId');

		/** @var EripPayment $payment */
		$payment = $this->getDoctrine()->getRepository('BankMainBundle:EripPayment')->find($paymentId);
		if(!$payment){
			throw new \Exception('Payment not found');
		}

		$fields = array();
		$passedFields = $request->get('fields');
		foreach($payment->getFields() as $f){
			if(!isset($passedFields[$f->getName()])){
				throw new \Exception(sprintf('Missing "%s" field', $f->getName()));
			}

			$value = $passedFields[$f->getName()];

			if(!preg_match('/'.$f->getRegex().'/', $value)){
				throw new \Exception(sprintf('Field %s doesn\'t match the pattern "%s"', $f->getName(), $f->getRegex()));
			}

			$fields[$f->getName()] = $passedFields[$f->getName()];
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
			$payment->getName(),
			json_encode($fields),
			$amount
		);

		try{
			$this->get('api')->writeOff($account, $amount);
		}catch(\Exception $e){
			$this->get('monolog.logger.erip')->info($message.'FAIL');

			throw $e;
		}

		$operation = new Operation();
		$operation
			->setGiverAccount($account)
			->setType('erip')
			->setPaymentInfo(array(
				'payment' => $payment->getName(),
				'fields' => $fields
			))
			->setAmount($amount)
		;
		$em = $this->getDoctrine()->getManager();
		$em->persist($operation);
		$em->flush();

		$this->get('monolog.logger.erip')->info($message.'SUCCESS');

		return $this->view(array('success' => true));
	}
}
