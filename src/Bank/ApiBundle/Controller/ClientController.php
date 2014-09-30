<?php

namespace Bank\ApiBundle\Controller;

use Bank\MainBundle\Entity\Client;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class ClientController extends FOSRestController
{
	const AUTH_FAILED_CODE = 1;
	const AUTH_FAILED_MESSAGE = 'Authentication failed';
	const TOKEN_EXPIRED_CODE = 2;
	const TOKEN_EXPIRED_MESSAGE = 'Token Expired';

	public function listAction(Request $request){
		/** @var QueryBuilder $qb */
		$qb = $this->getDoctrine()->getRepository('BankMainBundle:Client')->createQueryBuilder('c');
		$qb
			->select('c.firstName, c.middleName, c.lastName, c.passportSeries, c.passportNumber')
			->setMaxResults(10)
		;

		if($query = $request->get('query')){
			$words = explode(' ', $query);
			foreach($words as $k => $w){
				$p = 'param'.$k;
				$qb
					->andWhere("c.firstName LIKE :$p OR c.middleName LIKE :$p OR c.lastName LIKE :$p OR c.passportSeries LIKE :$p")
					->setParameter($p, "%$w%");
				;
			}
		}

		$searchFields = array('firstName', 'middleName', 'lastName', 'passportSeries');
		foreach($searchFields as $f){
			if($v = $request->get($f)){
				$qb
					->andWhere("c.$f LIKE :$f")
					->setParameter($f, "%$v%");
				;
			}
		}

		if($passportNumber = $request->get('passportNumber')){
			$qb
				->andWhere("c.passportNumber = :passportNumber")
				->setParameter('passportNumber', $passportNumber);
			;
		}

		$clients = $qb->getQuery()->getResult();

		return $this->view($clients);
	}

	public function authAction(Request $request){
		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();

		/** @var Client $client */
		$client = $em->getRepository('BankMainBundle:Client')->find($request->get('id'));

		if(!$client){
			throw new \Exception(self::AUTH_FAILED_MESSAGE, self::AUTH_FAILED_CODE);
		}

		/** @var PasswordEncoderInterface $encoder */
		$encoder = $this->get('security.encoder_factory')->getEncoder($client);
		$encodedPassword = $encoder->encodePassword($request->get('password'), $client->getSalt());

		if($client->getPassword() != $encodedPassword){
			throw new \Exception(self::AUTH_FAILED_MESSAGE, self::AUTH_FAILED_CODE);
		}

		$client
			->setToken(str_shuffle(md5(time()).implode('', array_merge(range('0', '9'), range('a', 'z')))))
			->setTokenExpirationDate(new \DateTime('+1 hour'))
		;

		$em->persist($client);
		$em->flush();

		return $this->view(array(
			'token' => $client->getToken(),
			'expirationDate' => $client->getTokenExpirationDate()->format('r'),
		));
	}

	public function changePasswordAction(Request $request){
		$client = $this->authorize();

		$client->setPlainPassword($request->get('password'));

		$em = $this->getDoctrine()->getManager();
		$em->persist($client);
		$em->flush();

		return $this->view(array(
			'success' => true
		));
	}

	/**
	 * @return Client
	 * @throws \Exception
	 */
	private function authorize(){
		/** @var Request $request */
		$request = $this->get('request');

		/** @var EntityManager $em */
		$em = $this->getDoctrine()->getManager();

		/** @var Client $client */
		$client = $em->getRepository('BankMainBundle:Client')->findOneBy(array(
			'token' => $request->get('token')
		));

		if(!$client){
			throw new \Exception(self::AUTH_FAILED_MESSAGE, self::AUTH_FAILED_CODE);
		}

		$now = new \DateTime();
		if($client->getTokenExpirationDate() < $now){
			throw new \Exception(self::TOKEN_EXPIRED_MESSAGE, self::TOKEN_EXPIRED_CODE);
		}

		return $client;
	}
}
