<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /* @var $connection Connection */
        $connection = $this->get('doctrine')->getConnection();

        $result = $connection->executeQuery("SELECT NOW();");

        $now = $result->fetchColumn();

        return $this->render('AppBundle:default:index.html.twig', ['now' => $now]);
    }
}