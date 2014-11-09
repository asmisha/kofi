<?php
/**
 * User: asmisha
 * Date: 01.11.14
 * Time: 14:54
 */

namespace Bank\MainBundle\Command;


use Bank\MainBundle\Entity\Currency;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ParseCurrenciesCommand extends ContainerAwareCommand{

	protected function configure(){
		$this
			->setName('cron:parse:currencies')
		;
	}

	public function execute(InputInterface $input, OutputInterface $output){
		$xml = file_get_contents(sprintf('http://www.nbrb.by/Services/XmlExRates.aspx?ondate=%s', date('m/d/Y')));
		$xml = simplexml_load_string($xml);

		/** @var EntityManager $em */
		$em = $this->getContainer()->get('doctrine')->getManager();
		foreach($xml->Currency as $i){
			$c = $em->getRepository('BankMainBundle:Currency')->findOneBy(array(
				'code' => $i->CharCode
			));

			if(!$c){
				$c = new Currency();
				$c->setNameLocalized(array('ru' => $i->Name));
			}

			$c
				->setName($i->Name)
				->setCode($i->CharCode)
				->setRate($i->Rate)
			;

			$em->persist($c);
			$em->flush();
		}
	}

} 