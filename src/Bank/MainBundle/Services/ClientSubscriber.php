<?php
/**
 * User: asmisha
 * Date: 26.09.14
 * Time: 12:05
 */

namespace Bank\MainBundle\Services;

use Bank\MainBundle\Entity\Client;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\SecurityContext;

class ClientSubscriber implements EventSubscriber {
	/** @var \Symfony\Component\DependencyInjection\Container $container */
	private $container;
	/** @var  EncoderFactoryInterface */
	private $encoderFactory;

	public function __construct(Container $container){
		$this->container = $container;
		$this->encoderFactory = $container->get('security.encoder_factory');
	}

	public function getSubscribedEvents()
	{
		return array(
			'prePersist',
			'preUpdate',
		);
	}

	public function prePersist(LifecycleEventArgs $args) {
		$entity = $args->getEntity();

		if($entity instanceof Client) {
			$this->clientChanged($entity);
		}
	}

	public function preUpdate(LifecycleEventArgs $args){
		$entity = $args->getEntity();

		if($entity instanceof Client) {
			$this->clientChanged($entity);
		}
	}

	protected function getEncoder(Client $client)
	{
		return $this->encoderFactory->getEncoder($client);
	}

	private function clientChanged(Client $client){
		if (0 !== strlen($password = $client->getPlainPassword())) {
			$encoder = $this->getEncoder($client);
			$client->setPassword($encoder->encodePassword($password, $client->getSalt()));
			$client->eraseCredentials();
		}
	}
}