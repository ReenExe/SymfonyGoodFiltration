<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    public  function listAction(Request $request)
    {
        return $this->render('AppBundle:book:list.html.twig');
    }
}