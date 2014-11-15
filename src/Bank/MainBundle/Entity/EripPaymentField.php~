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
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $regex = '/^.*$/';


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
     * @param string $text
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
     * @return string 
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
     * @var \Bank\MainBundle\Entity\EripPayment
     */
    private $payment;


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
