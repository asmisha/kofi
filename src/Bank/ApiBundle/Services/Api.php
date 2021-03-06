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

	const NOTIFICATION_TYPE_NEW_DEPOSIT = 'newDeposit';
	const NOTIFICATION_TYPE_NEW_PAYMENT = 'newPayment';
	const NOTIFICATION_TYPE_CAPITALIZATION = 'capitalization';

	/** @var EntityManager */
	private $em;
	/** @var RequestStack */
	private $requestStack;
	/** @var Logger */
	private $logger;
	private $notificationUrl;

	const CLIENT_ID_KEY = 'clientId';
	const ACCOUNT_ID_KEY = 'accountId';

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

		$clientId = $request->get(self::CLIENT_ID_KEY);

		/** @var Client $client */
		$client = $this->em->getRepository('BankMainBundle:Client')->findOneBy(array(
			'id' => $clientId
		));

		if(!$client || !is_numeric($clientId) || !$client->getIsActive()){
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
			'id' => $request->get(self::ACCOUNT_ID_KEY)
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
	 * @param Currency $currency
	 * @param bool $notify
	 * @param Account|integer|null $payerAccount
	 */
	public function deposit($account, $amount, $currency, $notify = false, $payerAccount = null){
		$account = $this->normalizeAccount($account);
		$payerAccount = $payerAccount ? $this->normalizeAccount($payerAccount) : $payerAccount;

		$account->setBalance($account->getBalance() + $amount * $currency->getRate() / $account->getCurrency()->getRate());
		$this->em->persist($account);
		$this->em->flush();

		if($notify){
			$this->notifyClient($account->getClient(), self::NOTIFICATION_TYPE_NEW_DEPOSIT, array(
				'initialAmount' => $amount,
				'initialCurrency' => $currency->getCode(),
				'accountAmount' => $amount * $currency->getRate() / $account->getCurrency()->getRate(),
				'accountCurrency' => $account->getCurrency()->getCode(),
				'payerAccountId' =>  $payerAccount ? $payerAccount->getId() : null,
				'recipientAccountId' =>  $account ? $account->getId() : null,
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

		return null;
	}

	public function notifyClient(Client $client, $type, $data){
		$ch = curl_init($this->notificationUrl);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

		$data['type'] = $type;

		$post = array(
			'clientId' => $client->getId(),
			'content' => json_encode($data),
		);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl/7.37.1');

		curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		$this->logger->info(sprintf(
			'Notification sent to url "%s" with post %s; response: %d',
			$this->notificationUrl,
			json_encode($post),
			$code
		));
		curl_close($ch);
	}
} 