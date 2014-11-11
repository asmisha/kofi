<?php

namespace Bank\ApiBundle\Controller;

use Bank\ApiBundle\Services\Api;
use Bank\MainBundle\Entity\Account;
use Bank\MainBundle\Entity\Card;
use Bank\MainBundle\Entity\Operation;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends FOSRestController
{
	public function payAction(Request $request){
		/** @var Account $account */
		$account = $this->get('api')->getAccount();

		$fields = array('recipientBank', 'payerName', 'code', 'recipientAccountId', 'recipientName', 'amount');
		$data = array();
		foreach($fields as $f){
			if(!$request->get($f)){
				if($f == 'payerName'){
					$data[$f] = sprintf('%s %s', $account->getClient()->getFirstName(), $account->getClient()->getLastName());
				}else{
					throw new \Exception("Missing \"$f\" field");
				}
			}else{
				$data[$f] = $request->get($f);
			}
		}

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

		$operation = new Operation();
		$operation->setGiverAccount($account);

		if($data['recipientBank'] == $this->get('service_container')->getParameter('bank_name')){
			$recipientAccount = $this->getDoctrine()->getRepository('BankMainBundle:Account')->find($data['recipientAccountId']);

			try{
				$this->get('api')->deposit($recipientAccount, $amount, $account->getCurrency(), true);
			}catch(\Exception $e){
				$this->get('monolog.logger.erip')->info($message.'FAIL');
				$this->get('api')->deposit($account, $amount, $account->getCurrency());

				throw $e;
			}

			$operation->setRecipientAccount($recipientAccount);
		}

		$operation
			->setType('direct')
			->setPaymentInfo($data)
			->setAmount($amount)
		;

		$em = $this->getDoctrine()->getManager();
		$em->persist($operation);
		$em->flush();

		$this->get('monolog.logger.erip')->info($message.'SUCCESS');

		return $this->view(array('success' => true));
	}

	public function reportAction(Request $request){
		/** @var Account $account */
		$account = $this->get('api')->getAccount();

		/** @var QueryBuilder $qb */
		$qb = $this->getDoctrine()->getRepository('BankMainBundle:Operation')->createQueryBuilder('o');

		$qb
			->select('o.type, o.amount, o.paymentInfo, o.processedAt')
			->addSelect('ra.id recipientAccountId, rc.firstName recipientFirstName, rc.lastName recipientLastName')
			->addSelect('ga.id giverAccountId, gc.firstName giverFirstName, gc.lastName giverLastName')
			->leftJoin('o.recipientAccount', 'ra')
			->leftJoin('ra.client', 'rc')
			->leftJoin('o.giverAccount', 'ga')
			->leftJoin('ga.client', 'gc')
			->where('o.recipientAccount = :account OR o.giverAccount = :account')
			->setParameter('account', $account)
		;

		if($dateFrom = $request->get('dateFrom')){
			if(is_numeric($dateFrom)){
				$dateFrom = new \DateTime();
				$dateFrom->setTimestamp($dateFrom);
			}else{
				$dateFrom = new \DateTime($dateFrom);
			}

			$dateFrom->setTime(0, 0, 0);

			$qb
				->andWhere('o.processedAt >= :dateFrom')
				->setParameter('dateFrom', $dateFrom)
			;
		}

		if($dateTo = $request->get('dateTo')){
			if(is_numeric($dateTo)){
				$dateTo = new \DateTime();
				$dateTo->setTimestamp($dateTo);
			}else{
				$dateTo = new \DateTime($dateTo);
			}

			$dateTo->setTime(23, 59, 59);

			$qb
				->andWhere('o.processedAt <= :dateTo')
				->setParameter('dateTo', $dateTo)
			;
		}

		if($type = $request->get('type')){
			$qb
				->andWhere('o.type = :type')
				->setParameter('type', $type)
			;
		}

		$operations = $qb->getQuery()->getResult();

		foreach($operations as $k=>$o){
			if($o['giverAccountId'] == $account->getId()){
				$operations[$k]['amount'] = -$o['amount'];
			}

			$operations[$k]['processedAt'] = $o['processedAt']->getTimestamp();
		}

		return $this->view($operations);
	}
}
