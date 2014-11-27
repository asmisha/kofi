<?php

namespace Bank\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EripPayment
 */
class EripPayment
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
    private $fields;

    /**
     * @var \Bank\MainBundle\Entity\EripCategory
     */
    private $category;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fields = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add fields
     *
     * @param \Bank\MainBundle\Entity\EripPaymentField $fields
     * @return EripPayment
     */
    public function addField(\Bank\MainBundle\Entity\EripPaymentField $fields)
    {
        $this->fields[] = $fields;
		$fields->setPayment($this);

        return $this;
    }

    /**
     * Remove fields
     *
     * @param \Bank\MainBundle\Entity\EripPaymentField $fields
     */
    public function removeField(\Bank\MainBundle\Entity\EripPaymentField $fields)
    {
		$fields->setPayment(null);
        $this->fields->removeElement($fields);
    }

    /**
     * Get fields
     *
     * @return \Doctrine\Common\Collections\Collection|EripPaymentField[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Set category
     *
     * @param \Bank\MainBundle\Entity\EripCategory $category
     * @return EripPayment
     */
    public function setCategory(\Bank\MainBundle\Entity\EripCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \Bank\MainBundle\Entity\EripCategory 
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @var array
     */
    private $name;


    /**
     * Set name
     *
     * @param array $name
     * @return EripPayment
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
