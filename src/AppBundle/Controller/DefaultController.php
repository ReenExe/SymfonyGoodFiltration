<?php

namespace AppBundle\Controller;

use Doctrine\DBAL\Connection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        /* @var $connection Connection */
        $connection = $this->get('doctrine')->getConnection();

        $result = $connection->executeQuery("SELECT NOW();");

        $now = $result->fetchColumn();

        return $this->render('default/index.html.twig', ['now' => $now]);
    }
}