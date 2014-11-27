<?php
/**
 * User: asmisha
 * Date: 01.11.14
 * Time: 14:54
 */

namespace Bank\MainBundle\Command;


use Bank\ApiBundle\Services\Api;
use Bank\MainBundle\Entity\Account;
use Bank\MainBundle\Entity\Currency;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CapitalizationCommand extends ContainerAwareCommand{

	protected function configure(){
		$this
			->setName('cron:capitalization:run')
		;
	}

	public function execute(InputInterface $input, OutputInterface $output){
		/** @var EntityManager $em */
		$em = $this->getContainer()->get('doctrine')->getManager();

		/** @var Logger $logger */
		$logger = $this->getContainer()->get('monolog.logger.capitalization');

		/** @var Api $api */
		$api = $this->getContainer()->get('api');

		/** @var Account[] $accounts */
		$accounts = $em->getRepository('BankMainBundle:Account')->findAll();

		$percent = 0.1;

		foreach($accounts as $a){
			$was = $a->getBalance();
			$a->setBalance($a->getBalance() * (1 + $percent / 100));
			$em->persist($a);

			$logger->info(sprintf('Capitalization of account %d, %.2f => %.2f', $a->getId(), $was, $a->getBalance()));
			$api->notifyClient($a->getClient(), 'capitalization', array(
				"accountAmountBefore" => $was,
				"accountAmountAfter" => $a->getBalance(),
				"capitalizationRate" => $percent / 100,
				"accountCurrency" => $a->getCurrency()->getCode(),
				"accountId" => $a->getId()
			));
		}

		$em->flush();
	}

} 