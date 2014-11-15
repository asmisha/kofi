<?php

namespace Bank\ApiBundle\Controller;

use Bank\ApiBundle\Services\Api;
use Bank\MainBundle\Entity\Client;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class ClientController extends BaseController
{
	public function listAction($id, Request $request){
		/** @var QueryBuilder $qb */
		$qb = $this->getDoctrine()->getRepository('BankMainBundle:Client')->createQueryBuilder('c');
		$qb
			->select('c, account, card')
			->leftJoin('c.accounts', 'account')
			->leftJoin('account.cards', 'card')
			->where('c.id = :id')
			->setParameter('id', $id)
		;

		/** @var Client $client */
		$client = $qb->getQuery()->getSingleResult();
		$result = array();

		if($client){
			$result = array(
				'id' => $client->getId(),
				'firstName' => $client->getFirstName(),
				'middleName' => $client->getMiddleName(),
				'lastName' => $client->getLastName(),
				'accounts' => array(),
			);

			foreach($client->getAccounts() as $account){
				$result['accounts'][] = array(
					'id' => $account->getId(),
					'balance' => $account->getBalance(),
					'currency' => $account->getCurrency()->getId(),
					'cards' => array(),
				);

				foreach($account->getCards() as $card){
					$result['accounts'][count($result['accounts']) - 1]['cards'][] = array(
						'number' => $card->getNumber(),
						'expiresAt' => $card->getExpiresAt()->getTimestamp()
					);
				}
			}
		}

		return $this->view($result);
	}

	public function authAction(Request $request){
		/** @var Client $client */
		$client = $this->get('api')->getClient();

		/** @var PasswordEncoderInterface $encoder */
		$encoder = $this->get('security.encoder_factory')->getEncoder($client);
		$encodedPassword = $encoder->encodePassword($request->get('password'), $client->getSalt());

		if($client->getPassword() != $encodedPassword){
			throw new \Exception(Api::AUTH_FAILED_MESSAGE, Api::AUTH_FAILED_CODE);
		}

		return $this->view(array(
			'success' => true,
		));
	}

	public function changePasswordAction(Request $request){
		/** @var Client $client */
		$client = $this->get('api')->getClient();

		$client->setPlainPassword($request->get('password'));

		$em = $this->getDoctrine()->getManager();
		$em->persist($client);
		$em->flush();

		return $this->view(array(
			'success' => true
		));
	}
}
