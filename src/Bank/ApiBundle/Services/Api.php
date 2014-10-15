<?php
/**
 * User: asmisha
 * Date: 15.10.14
 * Time: 13:00
 */

namespace Bank\ApiBundle\Services;


use Bank\MainBundle\Entity\Account;
use Bank\MainBundle\Entity\Client;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class Api {
	const AUTH_FAILED_CODE = 1;
	const AUTH_FAILED_MESSAGE = 'Authentication failed';
	const ACCOUNT_NOT_FOUND_CODE = 2;
	const ACCOUNT_NOT_FOUND_MESSAGE = 'Account not found';
	const INSUFFICIENT_FUNDS_CODE = 3;
	const INSUFFICIENT_FUNDS_MESSAGE = 'Insufficient funds';

	/** @var EntityManager */
	private $em;
	/** @var RequestStack */
	private $requestStack;

	function __construct($em, $requestStack)
	{
		$this->em = $em;
		$this->requestStack = $requestStack;
	}

	/**
	 * @return Client
	 * @throws \Exception
	 */
	public function getClient(){
		/** @var Request $request */
		$request = $this->requestStack->getCurrentRequest();

		/** @var Client $client */
		$client = $this->em->getRepository('BankMainBundle:Client')->findOneBy(array(
			'id' => $request->get('clientId')
		));

		if(!$client){
			throw new \Exception(self::AUTH_FAILED_MESSAGE, self::AUTH_FAILED_CODE);
		}

		return $client;
	}

	/**
	 * @return Account
	 * @throws \Exception
	 */
	public function getAccount(){
		/** @var Request $request */
		$request = $this->requestStack->getCurrentRequest();

		/** @var Account $account */
		$account = $this->em->getRepository('BankMainBundle:Account')->findOneBy(array(
			'id' => $request->get('accountId')
		));

		if(!$account){
			throw new \Exception(self::ACCOUNT_NOT_FOUND_MESSAGE, self::ACCOUNT_NOT_FOUND_CODE);
		}

		return $account;
	}

	/**
	 * @param Account|integer $account
	 * @param $amount
	 * @throws \Exception
	 */
	public function writeOff($account, $amount){
		$account = $this->normalizeAccount($account);

		if($account->getBalance() < $amount){
			throw new \Exception(self::INSUFFICIENT_FUNDS_MESSAGE, self::INSUFFICIENT_FUNDS_CODE);
		}

		$account->setBalance($account->getBalance() - $amount);
		$this->em->persist($account);
		$this->em->flush();

		/** @var Account $account */
		$account = $this->em->getRepository('BankMainBundle:Account')->find($account->getId());
		if($account->getBalance() < 0){
			$account->setBalance($account->getBalance() + $amount);
			$this->em->persist($account);
			$this->em->flush();

			throw new \Exception(self::INSUFFICIENT_FUNDS_MESSAGE, self::INSUFFICIENT_FUNDS_CODE);
		}
	}

	/**
	 * @param Account|integer $account
	 * @param $amount
	 */
	public function charge($account, $amount){
		$account = $this->normalizeAccount($account);

		$account->setBalance($account->getBalance() + $amount);
		$this->em->persist($account);
		$this->em->flush();
	}

	/**
	 * @param Account|integer $account
	 * @return null|Account
	 * @throws \Exception
	 */
	private function normalizeAccount($account){
		if($account instanceof $account){
			return $account;
		}elseif(intval($account)){
			return $this->em->getRepository('BankMainBundle:Account')->find($account);
		}else{
			throw new \Exception(self::ACCOUNT_NOT_FOUND_MESSAGE, self::ACCOUNT_NOT_FOUND_CODE);
		}
	}
} 