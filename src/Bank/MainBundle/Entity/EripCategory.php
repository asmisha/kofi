<?php

namespace Bank\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EripCategory
 */
class EripCategory
{
    /**
     * @var integer
     */
    private $id;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $payments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->payments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add payments
     *
     * @param \Bank\MainBundle\Entity\EripPayment $payments
     * @return EripCategory
     */
    public function addPayment(\Bank\MainBundle\Entity\EripPayment $payments)
    {
        $this->payments[] = $payments;

        return $this;
    }

    /**
     * Remove payments
     *
     * @param \Bank\MainBundle\Entity\EripPayment $payments
     */
    public function removePayment(\Bank\MainBundle\Entity\EripPayment $payments)
    {
        $this->payments->removeElement($payments);
    }

    /**
     * Get payments
     *
     * @return \Doctrine\Common\Collections\Collection|EripPayment[]
     */
    public function getPayments()
    {
        return $this->payments;
    }

	public function __toString(){
		return $this->name['en'] ? $this->name['en'] : '';
	}
    /**
     * @var array
     */
    private $name;


    /**
     * Set name
     *
     * @param array $name
     * @return EripCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return array 
     */
    public function getName()
    {
        return $this->name;
    }
}
