<?php

namespace Bank\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EripPaymentField
 */
class EripPaymentField
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $text;

    /**
     * @var string
     */
    private $regex;

    /**
     * @var array
     */
    private $errorMessages;

    /**
     * @var \Bank\MainBundle\Entity\EripPayment
     */
    private $payment;


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
     * Set name
     *
     * @param string $name
     * @return EripPaymentField
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set text
     *
     * @param array $text
     * @return EripPaymentField
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return array 
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set regex
     *
     * @param string $regex
     * @return EripPaymentField
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;

        return $this;
    }

    /**
     * Get regex
     *
     * @return string 
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * Set errorMessages
     *
     * @param array $errorMessages
     * @return EripPaymentField
     */
    public function setErrorMessages($errorMessages)
    {
        $this->errorMessages = $errorMessages;

        return $this;
    }

    /**
     * Get errorMessages
     *
     * @return array 
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Set payment
     *
     * @param \Bank\MainBundle\Entity\EripPayment $payment
     * @return EripPaymentField
     */
    public function setPayment(\Bank\MainBundle\Entity\EripPayment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \Bank\MainBundle\Entity\EripPayment 
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
