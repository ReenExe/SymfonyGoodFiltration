<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MySQLConnectionTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setUp()
    {
        self::bootKernel();
        $this->container = self::$kernel->getContainer();
    }

    public function testFirst()
    {
        /* @var $connection \Doctrine\DBAL\Connection */
        $connection = $this->container->get('doctrine')->getConnection();

        $connection->executeQuery("
            CREATE TABLE IF NOT EXISTS `first_connection`(
              `id`INT PRIMARY KEY AUTO_INCREMENT
            );
        ");

        $connection->executeQuery("
            INSERT INTO `first_connection` (`id`)
            VALUES (1);
        ");

        $connection->executeQuery("
            UPDATE `first_connection`
            SET `id` = 3
            WHERE `id` = 1;
        ");

        $id = $connection
            ->executeQuery("
                SELECT `id`
                FROM `first_connection`
                WHERE `id` = 3;
            ")
            ->fetchColumn();

        $this->assertEquals($id, 3);

        $connection->executeQuery("DROP TABLE `first_connection`");
    }
}