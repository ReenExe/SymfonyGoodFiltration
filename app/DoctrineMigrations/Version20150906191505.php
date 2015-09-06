<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150906191505 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('
            CREATE TABLE `rr_book`(
              `id` INT PRIMARY KEY AUTO_INCREMENT,
              `title` VARCHAR(255),
              `description` VARCHAR(255)
            )
        ');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {}
}
