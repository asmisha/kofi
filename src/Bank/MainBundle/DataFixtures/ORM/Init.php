<?php
/**
 * User: asmisha
 * Date: 23.09.14
 * Time: 17:31
 */

namespace Bank\MainBundle\DataFixtures\ORM;


use Application\Sonata\UserBundle\Entity\User;
use Bank\MainBundle\Entity\Account;
use Bank\MainBundle\Entity\Card;
use Bank\MainBundle\Entity\Client;
use Bank\MainBundle\Entity\Currency;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class Init implements FixtureInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $om)
	{
//		$userAdmin = new User();
//		$userAdmin
//			->setUsername('admin')
//			->setUsernameCanonical('admin')
//			->setEmail('asmisha@tut.by')
//			->setEmailCanonical('asmisha@tut.by')
//			->setPlainPassword('admin')
//			->setEnabled(true)
//			->addRole('ROLE_SUPER_ADMIN')
//			->setSuperAdmin(true)
//		;
//
//		$om->persist($userAdmin);

		$currency = new Currency();
		$currency
			->setCode('BYR')
			->setName('Belorussian Ruble')
			->setNameLocalized(array())
			->setRate(1)
		;
		$om->persist($currency);

		$names = array(
			'Tess Murowchick',
			'Niles Ladd',
			'Jannel Guenard',
			'Alano Gunn',
			'Alexandra Livernash',
			'Falkner Brandenburg',
			'Norina Hargreaves',
			'Levin Iannaccone',
			'Meriel Dewolfe',
			'Mason Cavallo',
			'Brinn Bunker',
			'Cale Corradi',
			'Katerina Rodriquez',
			'Gaylord Lepore',
			'Deirdre Offen',
			'Kennan Teuteberg',
			'Merrielle Galileo',
			'Hastings Keys',
			'Simonette Shoesmith',
			'Towney Edner',
			'Cthrine Persad',
			'Woodman Paracelsus',
			'Phyllys Murdoch',
			'Silvano Betti',
			'Jessie Ladd',
			'Tito Bombard',
			'Mallorie Fanton',
			'Kipp Ayres',
		);
		$middleNames = array(
			'Gerry',
			'Raphael',
			'Maryjo',
			'Eldredge',
			'Fancy',
			'Angelico',
			'Flora',
			'Jared',
			'Mallissa',
			'Far',
			'Darya',
			'Paddie',
			'Teresina',
			'Talbot',
			'Rosy',
			'Fabien',
			'Donnie',
			'Roberto',
			'Pris',
			'Rocky',
			'Mariam',
			'Dicky',
			'Violette',
			'Arri',
			'Perla',
			'Morie',
		);

		for($i = 0; $i < 10; $i++){
			$name = explode(' ', $names[array_rand($names)]);

			$client = new Client();
			$client
				->setFirstName($name[0])
				->setLastName($name[1])
				->setMiddleName($middleNames[array_rand($middleNames)])
				->setPassportIssueAuthority('DIA')
				->setPassportIssueDate(new \DateTime())
				->setPassportNumber(mt_rand(1000000, 9999999))
				->setPassportSeries($name[0][0].$name[1][0])
			;

			for($j = mt_rand(0, 2); $j >= 0; $j--){
				$account = new Account();

				$account
					->setBalance(mt_rand(0, 1000000))
					->setClient($client)
					->setCurrency($currency)
				;
				$om->persist($account);

				for($k = mt_rand(0, 2); $k >= 0; $k--){
					$card = new Card();
					$card
						->setAccount($account)
						->setCvv(mt_rand(100, 999))
						->setNumber(substr(str_shuffle(str_repeat(implode('', range(0, 9)), 10)), 0, 16))
						->setExpiresAt(new \DateTime(sprintf('+%d days', mt_rand(100, 1000))))
					;
					$om->persist($card);
				}
			}

			$om->persist($client);
		}

		$om->flush();
	}
}