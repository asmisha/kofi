<?php

namespace Bank\MainBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Account
 */
class Account
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var Currency
     */
    private $currency;

    /**
     * @var float
     */
    private $balance = 0;

	function __construct()
	{
		$this->createdAt = new \DateTime();
		$this->cards = new ArrayCollection();
	}


	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Account
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set currency
     *
     * @param Currency $currency
     * @return Account
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set balance
     *
     * @param float $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return float 
     */
    public function getBalance()
    {
        return $this->balance;
    }
    /**
     * @var \Bank\MainBundle\Entity\Client
     */
    private $client;


    /**
     * Set client
     *
     * @param \Bank\MainBundle\Entity\Client $client
     * @return Account
     */
    public function setClient(\Bank\MainBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Bank\MainBundle\Entity\Client 
     */
    public function getClient()
    {
        return $this->client;
    }

	function __toString()
	{
		return $this->id ? 'Account #'.$this->id : 'New Account';
	}
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $cards;


    /**
     * Add cards
     *
     * @param \Bank\MainBundle\Entity\Card $cards
     * @return Account
     */
    public function addCard(\Bank\MainBundle\Entity\Card $cards)
    {
        $this->cards[] = $cards;
		$cards->setAccount($this);

        return $this;
    }

    /**
     * Remove cards
     *
     * @param \Bank\MainBundle\Entity\Card $cards
     */
    public function removeCard(\Bank\MainBundle\Entity\Card $cards)
    {
        $this->cards->removeElement($cards);
		$cards->setAccount(null);
    }

    /**
     * Get cards
     *
     * @return \Doctrine\Common\Collections\Collection|Card[]
     */
    public function getCards()
    {
        return $this->cards;
    }
    /**
     * @var boolean
     */
    private $isActive = true;


    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Account
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
}
