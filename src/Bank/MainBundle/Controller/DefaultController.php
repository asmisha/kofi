<?php

namespace Bank\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('BankMainBundle:Default:index.html.twig', array('name' => $name));
    }
}
