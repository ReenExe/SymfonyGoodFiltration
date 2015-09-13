<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Book;
use Doctrine\ORM\EntityRepository;

class BookRepository extends EntityRepository
{
    /**
     * @param $limit
     * @param $offset
     * @return Book[]
     */
    public function getList($limit, $offset)
    {
        return $this->findBy([], [], $limit, $offset);
    }
}