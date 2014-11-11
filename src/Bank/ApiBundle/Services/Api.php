<?php
/**
 * User: asmisha
 * Date: 15.10.14
 * Time: 13:00
 */

namespace Bank\ApiBundle\Services;


use Bank\MainBundle\Entity\Account;
use Bank\MainBundle\Entity\Client;
use Bank\MainBundle\Entity\Currency;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
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
	/** @var Logger */
	private $logger;
	private $notificationUrl;

	function __construct($em, $requestStack, $logger, $notificationUrl)
	{
		$this->em = $em;
		$this->requestStack = $requestStack;
		$this->notificationUrl = $notificationUrl;
		$this->logger = $logger;
	}

	/**
	 * @return Client
	 * @throws \Exception
	 */
	public function getClient(){
		/** @var Request $request */
		$request = $this->requestStack->getCurrentRequest();

		$clientId = intval($request->get('clientId'));

		/** @var Client $client */
		$client = $this->em->getRepository('BankMainBundle:Client')->findOneBy(array(
			'id' => $clientId
		));

		if(!$client || $client->getId() != $clientId){
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
	 * @param Currency $currency
	 * @param float $amount
	 * @param bool $notify
	 */
	public function deposit($account, $amount, $currency, $notify = false){
		$account = $this->normalizeAccount($account);

		$account->setBalance($account->getBalance() + $amount * $currency->getRate() / $account->getCurrency()->getRate());
		$this->em->persist($account);
		$this->em->flush();

		if($notify){
			$this->notifyClient($account->getClient(), sprintf(
				"You've been deposited %.2f %s (%.2f %s)",
				$amount,
				$currency->getCode(),
				$amount * $currency->getRate() / $account->getCurrency()->getRate(),
				$account->getCurrency()->getCode()
			));
		}
	}

	/**
	 * @param Account|integer $account
	 * @return null|Account
	 * @throws \Exception
	 */
	private function normalizeAccount($account){
		if($account instanceof Account){
			return $account;
		}elseif(intval($account)){
			$account = $this->em->getRepository('BankMainBundle:Account')->find($account);

			if(!($account instanceof Account)){
				throw new \Exception(self::ACCOUNT_NOT_FOUND_MESSAGE, self::ACCOUNT_NOT_FOUND_CODE);
			}

		}else{
			throw new \Exception(self::ACCOUNT_NOT_FOUND_MESSAGE, self::ACCOUNT_NOT_FOUND_CODE);
		}
	}

	public function notifyClient(Client $client, $message){
		$ch = curl_init($this->notificationUrl);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			'clientId' => $client->getId(),
			'content' => $message
		));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$this->logger->info(sprintf(
			'Notification sent to client %d with message "%s"; response: %d',
			$client->getId(),
			$message,
			$code
		));
	}
} 