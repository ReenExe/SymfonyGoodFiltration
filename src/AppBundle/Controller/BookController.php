<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    public  function listAction(Request $request)
    {
        $limit = $request->query->get('limit', 100);
        $offset = $request->query->get('offset', 100);

        $books = $this
            ->get('doctrine')
            ->getRepository('AppBundle:Book')
            ->getList($limit, $offset);

        $result = [];
        foreach ($books as $book) {
            $result[] = [
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
            ];
        }

        return $this->render('AppBundle:book:list.html.twig', [
            'items' => $result
        ]);
    }
}