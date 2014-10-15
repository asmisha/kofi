<?php

namespace Bank\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Card
 */
class Card
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $number;

    /**
     * @var string
     */
    private $cvv;

    /**
     * @var \DateTime
     */
    private $expiresAt;


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
     * Set number
     *
     * @param string $number
     * @return Card
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set cvv
     *
     * @param string $cvv
     * @return Card
     */
    public function setCvv($cvv)
    {
        $this->cvv = $cvv;

        return $this;
    }

    /**
     * Get cvv
     *
     * @return string 
     */
    public function getCvv()
    {
        return $this->cvv;
    }

    /**
     * Set expiresAt
     *
     * @param \DateTime $expiresAt
     * @return Card
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt
     *
     * @return \DateTime 
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }
    /**
     * @var \Bank\MainBundle\Entity\Account
     */
    private $account;


    /**
     * Set account
     *
     * @param \Bank\MainBundle\Entity\Account $account
     * @return Card
     */
    public function setAccount(\Bank\MainBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \Bank\MainBundle\Entity\Account 
     */
    public function getAccount()
    {
        return $this->account;
    }

	public function __toString(){
		return substr_replace($this->getNumber(), str_repeat('*', 8), 4, 8);
	}
}
