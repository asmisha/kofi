<?php

namespace Bank\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Operation
 */
class Operation
{
    /**
     * @var integer
     */
    private $id;

	function __construct(){
		$this->processedAt = new \DateTime();
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
     * @var \Bank\MainBundle\Entity\Account
     */
    private $giverAccount;

    /**
     * @var \Bank\MainBundle\Entity\Account
     */
    private $recipientAccount;


    /**
     * Set giverAccount
     *
     * @param \Bank\MainBundle\Entity\Account $giverAccount
     * @return Operation
     */
    public function setGiverAccount(\Bank\MainBundle\Entity\Account $giverAccount = null)
    {
        $this->giverAccount = $giverAccount;

        return $this;
    }

    /**
     * Get giverAccount
     *
     * @return \Bank\MainBundle\Entity\Account 
     */
    public function getGiverAccount()
    {
        return $this->giverAccount;
    }

    /**
     * Set recipientAccount
     *
     * @param \Bank\MainBundle\Entity\Account $recipientAccount
     * @return Operation
     */
    public function setRecipientAccount(\Bank\MainBundle\Entity\Account $recipientAccount = null)
    {
        $this->recipientAccount = $recipientAccount;

        return $this;
    }

    /**
     * Get recipientAccount
     *
     * @return \Bank\MainBundle\Entity\Account 
     */
    public function getRecipientAccount()
    {
        return $this->recipientAccount;
    }
    /**
     * @var float
     */
    private $amount;

    /**
     * @var \DateTime
     */
    private $processedAt;

    /**
     * Set amount
     *
     * @param float $amount
     * @return Operation
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float 
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set processedAt
     *
     * @param \DateTime $processedAt
     * @return Operation
     */
    public function setProcessedAt($processedAt)
    {
        $this->processedAt = $processedAt;

        return $this;
    }

    /**
     * Get processedAt
     *
     * @return \DateTime 
     */
    public function getProcessedAt()
    {
        return $this->processedAt;
    }
    /**
     * @var array
     */
    private $paymentInfo;


    /**
     * Set paymentInfo
     *
     * @param array $paymentInfo
     * @return Operation
     */
    public function setPaymentInfo($paymentInfo)
    {
        $this->paymentInfo = $paymentInfo;

        return $this;
    }

    /**
     * Get paymentInfo
     *
     * @return array 
     */
    public function getPaymentInfo()
    {
        return $this->paymentInfo;
    }
    /**
     * @var string
     */
    private $type;


    /**
     * Set type
     *
     * @param string $type
     * @return Operation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
}
